<?php

namespace App\Http\Controllers\Api;

use App\Helpers\UploadImageHelper;
use App\Http\Controllers\Controller;
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
        $potholes = auth()->user()->potholes()->paginate(20);
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
        if (!auth()->user()->can('CREATE_POTHOLES')) {
            return $this->sendError('Error.', 'You are not authorized to create potholes.', 403);
        }

        try {
            
            $potholeData = $request->safe()->except('image');
            $potholeData['image'] = UploadImageHelper::uploadImage($request->get('image'), 'potholes/', 'pothole_');

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
        return $this->sendResponse(['pothole' => PotholeResource::make($pothole)], 'Pothole retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePotholeRequest $request, Pothole $pothole)
    {
        if (!auth()->user()->can('UPDATE_POTHOLES')) {
            return $this->sendError('Update Error.', 'You do not have permission to update this pothole.', 403);
        }

        try {
            $potholeData = $request->safe()->except('image');

            if ($request->has('image')) {
                $potholeData['image'] = UploadImageHelper::uploadImage($request->get('image'), 'potholes/', 'pothole_');
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
        if (!auth()->user()->can('DELETE_POTHOLES')) {
            return $this->sendError('Delete Error.', 'You do not have permission to delete this pothole.', 403);
        }

        try {
            $pothole->delete();
            return $this->sendResponse([], 'Pothole deleted successfully.');
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

                $predictions = $responseData['prediction'];
                $pothole->update(['predictions' => $predictions]);

                return $this->sendResponse([
                    'predictions' => $predictions
                ], 'Pothole predicted successfully.');
            } else {
                return $this->sendError('Predict Server Error.', 'The prediction server returned an error.', $response->status());
            }
        } catch (\Exception $e) {
            Log::error('Predict Pothole Error: ' . $e->getMessage(), ['exception' => $e]);
            return $this->sendError('Predict Error.', 'An error occurred while predicting the pothole.', 500);
        }
    }
}
