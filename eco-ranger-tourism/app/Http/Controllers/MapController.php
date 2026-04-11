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
            ],
            [
                'id' => 2, 
                'name' => 'Taman Nasional Komodo, NTT', 
                'lat' => -8.55, 
                'lng' => 119.42, 
                'status' => 'yellow',
                'description' => 'Area Konservasi. Pengunjung wajib didampingi ranger.'
            ],
            [
                'id' => 3, 
                'name' => 'Gunung Bromo, Jawa Timur', 
                'lat' => -7.94, 
                'lng' => 112.95, 
                'status' => 'red',
                'description' => 'Status Waspada. Aktivitas vulkanik meningkat.'
            ],
            [
                'id' => 4, 
                'name' => 'Raja Ampat, Papua Barat', 
                'lat' => -0.58, 
                'lng' => 130.25, 
                'status' => 'green',
                'description' => 'Surga Bawah Laut. Spot diving terbaik dunia.'
            ],
            [
                'id' => 5, 
                'name' => 'Pantai Kuta, Bali', 
                'lat' => -8.71, 
                'lng' => 115.16, 
                'status' => 'red',
                'description' => 'Area Pantau: Terdapat laporan tumpukan sampah plastik.'
            ]
        ];
        
        return view('map', compact('locations'));
    }
}
