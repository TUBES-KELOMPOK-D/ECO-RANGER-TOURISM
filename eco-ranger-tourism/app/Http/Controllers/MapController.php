<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Location;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Destinasi;


class MapController extends Controller
{
    public function index()
    {
        $locations = Destinasi::all();
        return view('map', compact('locations'));
    }
}
