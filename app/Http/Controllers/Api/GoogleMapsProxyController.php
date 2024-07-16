<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GoogleMapsProxyController extends Controller
{
    public function js(Request $request)
    {
        $params = $request->query();
        $params['key'] = env('GOOGLE_MAPS_API_KEY');

        $response = Http::get('https://maps.googleapis.com/maps/api/js', $params);

        return response($response->body())->header('Content-Type', 'application/javascript');
    }

    public function place(Request $request, $endpoint)
    {
        $params = $request->query();
        $params['key'] = env('GOOGLE_MAPS_API_KEY');

        $response = Http::get('https://maps.googleapis.com/maps/api/place/' . $endpoint, $params);

        return response($response->body())->header('Content-Type', 'application/json; charset=UTF-8');
    }

    public function geocode(Request $request, $endpoint)
    {
        $params = $request->query();
        $params['key'] = env('GOOGLE_MAPS_API_KEY');

        $response = Http::get('https://maps.googleapis.com/maps/api/geocode/' . $endpoint, $params);

        return response($response->body())->header('Content-Type', 'application/json; charset=UTF-8');
    }
}
