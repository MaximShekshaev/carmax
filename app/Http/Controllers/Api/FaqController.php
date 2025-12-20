<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Faq;

class FaqController extends Controller
{
    public function index()
    {
        return Faq::where('is_active', true)
            ->orderBy('id')
            ->get();
    }
}