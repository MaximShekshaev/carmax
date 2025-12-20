<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
   
    public function index()
    {
        return response()->json(
            Review::where('is_active', true)
                ->orderByDesc('created_at')
                ->get()
        );
    }

   
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'message' => 'required|string|max:1000',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $review = Review::create($data);

        return response()->json($review, 201);
    }
}