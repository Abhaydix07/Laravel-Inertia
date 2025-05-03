<?php

namespace App\Http\Controllers;
use App\Models\WebhookRequest;

use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->all();
        $ip = $request->ip();

        WebhookRequest::create([
            'payload' => json_encode($payload),
            'ip' => $ip,
        ]);

        // Send OneSignal push with "Request to accept notifications"
        // Include IP info or user identifier

        return response()->json(['message' => 'Webhook received']);
    }

}
