<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/webhook', [WebhookController::class, 'handle']);
Route::post('/accept-notification', [TokenController::class, 'accept']);
Route::post('/notify', [NotificationController::class, 'send']);
