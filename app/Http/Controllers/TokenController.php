<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\NotificationToken;



class TokenController extends Controller
{
    public function accept(Request $request)
    {
        $ip = $request->ip();
    
        $token = Str::random(40);
    
        NotificationToken::create([
            'ip' => $ip,
            'token' => $token,
        ]);
    
        return response()->json(['token' => $token]);
    }
    
}
