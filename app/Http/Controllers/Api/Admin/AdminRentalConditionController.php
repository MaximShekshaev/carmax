<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\RentalCondition;
use Illuminate\Http\Request;

class AdminRentalConditionController extends Controller
{
    
    public function index()
    {
        return response()->json(RentalCondition::all());
    }

    
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'icon' => 'nullable|string|max:255',
        ]);

        $condition = RentalCondition::create($data);
        return response()->json($condition, 201);
    }

   
    public function update(Request $request, $id)
    {
        $condition = RentalCondition::findOrFail($id);

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'icon' => 'nullable|string|max:255',
        ]);

        $condition->update($data);
        return response()->json($condition);
    }

    
    public function destroy($id)
    {
        $condition = RentalCondition::findOrFail($id);
        $condition->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
