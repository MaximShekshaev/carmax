<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class AdminReviewController extends Controller
{
    public function index()
    {
        try {
            $reviews = Review::orderByDesc('created_at')->get();
            return response()->json($reviews);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error fetching reviews',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function toggle($id)
    {
        try {
            $review = Review::findOrFail($id);
            $review->is_active = !$review->is_active;
            $review->save();

            return response()->json($review);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error toggling review',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $review = Review::findOrFail($id);
            $review->delete();

            return response()->json(['message' => 'Deleted']);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error deleting review',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
