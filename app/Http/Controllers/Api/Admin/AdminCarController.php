<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Car;
use Illuminate\Http\Request;

class AdminCarController extends Controller
{
   
    public function index()
    {
        return response()->json(Car::all());
    }

    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'brand' => 'required|string',
            'price_per_day' => 'required|numeric',
            'image' => 'nullable|string',
            'description' => 'nullable|string',
            'available' => 'required|boolean',
        ]);

        $car = Car::create($request->all());

        return response()->json($car);
    }

    
    public function update(Request $request, $id)
    {
        $car = Car::findOrFail($id);
        $car->update($request->all());
        return response()->json($car);
    }

    
    public function destroy($id)
    {
        Car::destroy($id);
        return response()->json(['message' => 'Car deleted']);
    }
}
