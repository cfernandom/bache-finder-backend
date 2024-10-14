<?php

namespace App\Http\Controllers\Api;

use App\Constants\Pothole as ConstantsPothole;
use App\Enums\Roles;
use App\Helpers\ImageHelper;
use App\Http\Controllers\Controller;
use App\Helpers\ArrayHelper;
use App\Http\Requests\StorePotholeRequest;
use App\Http\Requests\UpdatePotholeRequest;
use App\Http\Resources\PotholeCollection;
use App\Http\Resources\PotholeResource;
use App\Models\Pothole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PotholeController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Pothole::class);

        if (auth()->user()->hasRole([Roles::ADMIN->value, Roles::INSTITUTION->value, Roles::SUPER_ADMIN->value])) {
            $potholes = Pothole::orderBy('created_at', 'desc')->paginate(20);
        } else {
            $potholes = auth()->user()->potholes()->orderBy('created_at', 'desc')->paginate(20);
        }
        
        return $this->sendResponse(
            new PotholeCollection($potholes),
            'Potholes retrieved successfully.'
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePotholeRequest $request)
    {
        $this->authorize('create', Pothole::class);

        try {

            $potholeData = $request->safe()->except('image');
            $potholeData['image'] = ImageHelper::uploadImage($request->get('image'), 'potholes/', 'pothole_');

            $pothole = auth()->user()->potholes()->create($potholeData);

            return $this->sendResponse([
                'pothole' => PotholeResource::make($pothole)
            ], 'Pothole created successfully.');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return $this->sendError('Error.', 'An error occurred while creating the pothole.', 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Pothole $pothole)
    {
        $this->authorize('view', $pothole);
        return $this->sendResponse(['pothole' => PotholeResource::make($pothole)], 'Pothole retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePotholeRequest $request, Pothole $pothole)
    {
        $this->authorize('update', $pothole);

        try {
            $potholeData = $request->safe()->except('image');

            if ($request->has('image')) {
                $potholeData['image'] = ImageHelper::uploadImage($request->get('image'), 'potholes/', 'pothole_');
            }

            $pothole->update($potholeData);

            return $this->sendResponse([
                'pothole' => PotholeResource::make($pothole->fresh())
            ], 'Pothole updated successfully.');
        } catch (\Exception $e) {
            Log::error('Update Pothole Error: ' . $e->getMessage(), ['exception' => $e]);
            return $this->sendError('Update Error.', 'An error occurred while creating the pothole.', 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pothole $pothole)
    {
        // if (!auth()->user()->can('DELETE_POTHOLES')) {
        //     return $this->sendError('Delete Error.', 'You do not have permission to delete this pothole.', 403);
        // }

        $this->authorize('delete', $pothole);

        try {
            $imageUrl = $pothole->image;
            if ($pos = strstr($imageUrl, '/storage/')) {
                $imageRelativePath = ltrim($pos, '/storage/');
            }
            $imageDeletedMessage = ImageHelper::deleteImage($imageRelativePath);
        
            $pothole->delete();
            return $this->sendResponse([], 'Pothole deleted successfully. ' . $imageDeletedMessage);
        } catch (\Exception $e) {
            Log::error('Delete Pothole Error: ' . $e->getMessage(), ['exception' => $e]);
            return $this->sendError('Delete Error.', 'An error occurred while deleting the pothole.', 500);
        }
    }

    public function predict(Pothole $pothole)
    {
        $imagePath = storage_path('app/public/' . str_replace(env('APP_URL') . '/storage/', '', $pothole->image));

        if (!file_exists($imagePath)) {
            return $this->sendError('Image Not Found', 'The image file does not exist on' . $imagePath, 404);
        }

        try {
            $imageContent = file_get_contents($imagePath);

            $response = Http::attach(
                'file',
                $imageContent,
                'pothole.jpg',
            )->post(env('ML_SERVER_URL') . '/predict');

            if ($response->successful()) {
                $responseData = $response->json();

                if (!isset($responseData['prediction'])) {
                    return $this->sendError('Invalid Response', 'The prediction server returned an invalid response.', 500);
                }

                $weights = $responseData['prediction'];
                $potholeType = ConstantsPothole::TYPES[ArrayHelper::getIndexOfLargestNumber($weights) + 1];

                return $this->sendResponse([
                    'weights' => $weights,
                    'type' => $potholeType,
                ], 'Pothole predicted successfully.');
            } else {
                Log::error('Predict Pothole Error: ' . $response->body(), ['response' => $response->json()]);
                return $this->sendError('Predict Server Error.', 'The prediction server returned an error.', $response->status());
            }
        } catch (\Exception $e) {
            Log::error('Predict Pothole Error: ' . $e->getMessage(), ['exception' => $e]);
            return $this->sendError('Predict Error.', 'An error occurred while predicting the pothole.', 500);
        }
    }

    public function storeAndPredict(StorePotholeRequest $request)
    {
        $this->authorize('create', Pothole::class);

        try {
            // Store the pothole
            $potholeData = $request->safe()->except('image');
            $potholeData['image'] = ImageHelper::uploadImage($request->get('image'), 'potholes/', 'pothole_');

            $pothole = auth()->user()->potholes()->create($potholeData);

            // Predict the pothole type
            $imagePath = storage_path('app/public/' . str_replace(env('APP_URL') . '/storage/', '', $pothole->image));

            if (!file_exists($imagePath)) {
                return $this->sendError('Image Not Found', 'The image file does not exist on ' . $imagePath, 404);
            }

            $imageContent = file_get_contents($imagePath);

            $response = Http::attach(
                'file',
                $imageContent,
                'pothole.jpg',
            )->post(env('ML_SERVER_URL') . '/predict');

            if ($response->successful()) {
                $responseData = $response->json();

                if (!isset($responseData['prediction'])) {
                    return $this->sendError('Invalid Response', 'The prediction server returned an invalid response.', 500);
                }

                $weights = $responseData['prediction'];
                $potholeType = ConstantsPothole::TYPES[ArrayHelper::getIndexOfLargestNumber($weights) + 1];

                $pothole->update(['type' => $potholeType]);
                $pothole->update(['predictions' => $weights]);

                return $this->sendResponse([
                    'pothole' => PotholeResource::make($pothole->fresh()),
                ], 'Pothole created and predicted successfully.');
            } else {
                Log::error('Predict Pothole Error: ' . $response->body(), ['response' => $response->json()]);
                return $this->sendError('Predict Server Error.', 'The prediction server returned an error.', $response->status());
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return $this->sendError('Error.', 'An error occurred while creating and predicting the pothole.', 500);
        }
    }
}
