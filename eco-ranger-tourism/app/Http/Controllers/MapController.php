<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Location;


class MapController extends Controller
{
    public function index()
    {
        // data dummy
        $locations = [
            [
                'id' => 1, 
                'name' => 'Muara Gembong, Jawa Barat', 
                'lat' => -6.01, 
                'lng' => 107.03, 
                'status' => 'green',
                'description' => 'Lokasi Penyeimbangan Karbon. Area penanaman mangrove.'
            ]
        ];
        
        return view('map', compact('locations'));
    }
}
