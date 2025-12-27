<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminAuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (
            $request->email !== env('ADMIN_EMAIL') ||
            $request->password !== env('ADMIN_PASSWORD')
        ) {
            return response()->json(['message' => 'Неверные данные'], 401);
        }

        return response()->json([
            'token' => env('ADMIN_TOKEN')
        ]);
    }
}
