<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GoogleMapsProxyController extends Controller
{
    public function proxy(Request $request)
    {
        $params = $request->query();
        $params['key'] = env('GOOGLE_MAPS_API_KEY');

        $response = Http::get('https://maps.googleapis.com/maps/api/js', $params);

        return response($response->body())->header('Content-Type', 'application/javascript');
    }
}
