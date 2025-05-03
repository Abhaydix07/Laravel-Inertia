<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\NotificationToken;
use App\Models\WebhookRequest;

class NotificationController extends Controller
{
    public function send(Request $request)
    {
        // Get token from header
        $token = $request->header('X-Notification-Token');

        // Validate token
        $notificationToken = NotificationToken::where('token', $token)
            ->where('is_active', true)
            ->first();

        if (!$notificationToken) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Get the latest webhook data for the same IP
        $webhookData = WebhookRequest::where('ip', $notificationToken->ip)
            ->latest()
            ->first();

        if (!$webhookData) {
            return response()->json(['error' => 'No webhook data'], 404);
        }

        // Send notification via OneSignal
        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . config('services.onesignal.rest_api_key'),
            'Content-Type' => 'application/json',
        ])->post('https://onesignal.com/api/v1/notifications', [
            'app_id' => config('services.onesignal.app_id'),
            'included_segments' => ['All'], // You can customize to send to specific users
            'contents' => [
                'en' => json_encode($webhookData->payload),
            ],
        ]);

        if ($response->failed()) {
            return response()->json(['error' => 'Failed to send notification'], 500);
        }

        return response()->json(['message' => 'Notification sent']);
    }
}
