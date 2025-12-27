<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rental;

class RentalController extends Controller
{
    
    public function index(Request $request)
    {
        $token = $request->bearerToken(); 
        $user = \App\Models\User::where('api_token', $token)->first();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $rentals = Rental::with('car')
            ->where('user_id', $user->id)
            ->get();

        return response()->json($rentals);
    }

    
    public function store(Request $request)
    {
        $token = $request->bearerToken();
        $user = \App\Models\User::where('api_token', $token)->first();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'car_id' => 'required|exists:cars,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $days = (strtotime($request->end_date) - strtotime($request->start_date)) / 86400 + 1;
        $car = \App\Models\Car::find($request->car_id);
        $totalPrice = $car->price_per_day * $days;

        $rental = Rental::create([
            'user_id' => $user->id,
            'car_id' => $request->car_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'total_price' => $totalPrice
        ]);

        return response()->json($rental, 201);
    }

    public function destroy($id)
{
    $rental = Rental::find($id);
    if (!$rental) {
        return response()->json(['message' => 'Аренда не найдена'], 404);
    }

    $rental->delete();
    return response()->json(['message' => 'Аренда удалена']);
}
}
