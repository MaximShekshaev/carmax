<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class AdminFaqController extends Controller
{
    
    public function index()
    {
        return response()->json(Faq::all());
    }

   
    public function store(Request $request)
    {
        $data = $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string|max:2000',
            'is_active' => 'required|boolean'
        ]);

        $faq = Faq::create($data);
        return response()->json($faq, 201);
    }

    
    public function update(Request $request, $id)
    {
        $faq = Faq::findOrFail($id);

        $data = $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string|max:2000',
            'is_active' => 'required|boolean'
        ]);

        $faq->update($data);
        return response()->json($faq);
    }

   
    public function destroy($id)
    {
        $faq = Faq::findOrFail($id);
        $faq->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
