<?php

namespace App\Http\Controllers;

use App\Models\RoutePoint;
use Illuminate\Http\Request;

class RoutePointController extends Controller
{
    public function store(Request $request)
{
    RoutePoint::create([
        'route_id' => $request->route_id,
        'title' => $request->title,
        'description' => $request->description,
        'lat' => $request->lat,
        'lng' => $request->lng,
        'sort_order' => $request->sort_order,
        'image' => $request->file('image') 
            ? $request->file('image')->store('route_points', 'public') 
            : null,
    ]);

    return back();
}

}
