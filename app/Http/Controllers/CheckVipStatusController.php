<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckVipStatusController extends Controller
{
    public function checkStatus()
    {
        $user = Auth::user();
        if ($user) {
            return response()->json([
                'is_vip' => $user->is_vip,
            ]);
        }

        return response()->json(['error' => 'User not authenticated'], 401);
    }
}