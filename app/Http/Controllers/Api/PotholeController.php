<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePotholeRequest;
use App\Http\Requests\UpdatePotholeRequest;
use App\Models\Pothole;

class PotholeController extends Controller
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
        //
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
