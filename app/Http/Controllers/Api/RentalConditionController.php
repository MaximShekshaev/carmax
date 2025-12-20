<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RentalCondition;

class RentalConditionController extends Controller
{
    public function index()
    {
        return response()->json(
            RentalCondition::all()
        );
    }
}
