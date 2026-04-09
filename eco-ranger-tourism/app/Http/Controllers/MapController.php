<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Location;


class MapController extends Controller
{
    public function index()
    {
        $locations = [
        ];
        
        return view('map', compact('locations'));
    }
}
