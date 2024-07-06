<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePotholeRequest;
use App\Http\Requests\UpdatePotholeRequest;
use App\Http\Resources\PotholeResource;
use App\Models\Pothole;
use Illuminate\Support\Facades\Log;

class PotholeController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePotholeRequest $request)
    {
        try {

            $potholeData = $request->validated();
            $potholeData['image'] = $request->file('image')->store('potholes', 'public');
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePotholeRequest $request, Pothole $pothole)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pothole $pothole)
    {
        //
    }
}
