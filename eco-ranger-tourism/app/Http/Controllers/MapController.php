<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Location;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Destinasi;
use App\Models\Marker;


class MapController extends Controller
{
    public function index()
    {
        // Menampilkan data markers
        $markers = Marker::all();

        return view('map', compact('markers'));
    }

    public function store(Request $request)
    {
        $marker = new Marker();
        $marker->shape_type = $request->input('shape_type', 'Marker');
        $marker->status = $request->input('status', 'green');
        $marker->title = $request->input('title');
        $marker->description = $request->input('description');
        $marker->coordinates = json_encode($request->input('coordinates', []));
        $marker->radius = $request->input('radius');
        
        $marker->user_id = auth()->id(); 
        $marker->map_layer_id = null; 
        
        $marker->save();

        return response()->json(['message' => 'Berhasil menyimpan lokasi']);
    }
}
