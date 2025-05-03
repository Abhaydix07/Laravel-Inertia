<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>NotifyHub - Web Push Notifications</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs" defer></script>
    <script src="OneSignalSDKWorker.js" defer></script>
    <script src="https://cdn.onesignal.com/sdks/web/v16/OneSignalSDK.page.js" defer></script>
    <script>
        window.OneSignalDeferred = window.OneSignalDeferred || [];
        OneSignalDeferred.push(async function(OneSignal) {
            await OneSignal.init({
                appId: "5c95c939-c5ee-417b-83bf-5a5150f51d30",
            });
        });
    </script>
</head>

<body class="antialiased bg-gray-50">
    <div x-data="{
        isSubscribed: false,
        error: null,
        async subscribe() {
            try {
                this.error = null;
                // Initialize OneSignal if not already initialized
                if (!window.OneSignal) {
                    throw new Error('OneSignal not loaded');
                }

                // Get the current subscription state
                const isPushSupported = await OneSignal.isPushNotificationsSupported();
                if (!isPushSupported) {
                    throw new Error('Push notifications are not supported by this browser');
                }

                // Get the current permission state
                const permission = await OneSignal.Notifications.permission;
                if (permission === 'denied') {
                    throw new Error('Permission not granted for notifications');
                }

                // Request permission and subscribe
                const permissionResponse = await OneSignal.Notifications.requestPermission();
                if (permissionResponse) {
                    this.isSubscribed = true;
                    console.log('Successfully subscribed to notifications');
                } else {
                    throw new Error('Failed to subscribe to notifications');
                }
            } catch (err) {
                this.error = err.message;
                console.error('Error subscribing to notifications:', err);
            }
        }
    }" class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="flex-shrink-0 flex items-center">
                            <span class="text-2xl font-bold text-indigo-600">NotifyHub</span>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl font-bold text-gray-900 sm:text-5xl md:text-6xl">
                    <span class="block">Web Push</span>
                    <span class="block text-indigo-600">Notifications</span>
                </h1>
                <p class="mt-3 text-base text-gray-500 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl">
                    Get instant browser notifications for important updates.
                </p>
            </div>

            <!-- Subscription Form or Status -->
            <div class="mt-10 max-w-md mx-auto">
                <div x-show="!isSubscribed" class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Enable Push Notifications</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                Click the button below to enable browser notifications. You'll need to allow
                                notifications when prompted.
                            </p>
                        </div>

                        <div>
                            <button @click="subscribe"
                                class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Enable Notifications
                            </button>
                        </div>

                        <div x-show="error" class="mt-4 p-4 bg-red-50 rounded-md">
                            <p class="text-sm text-red-700" x-text="error"></p>
                        </div>
                    </div>
                </div>

                <div x-show="isSubscribed" class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Notifications Enabled</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                You're all set! You'll receive notifications in your browser when there are updates.
                            </p>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-md">
                            <p class="text-sm text-gray-800">
                                You can manage your notification preferences in your browser settings.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-white">
            <div class="max-w-7xl mx-auto py-12 px-4 overflow-hidden sm:px-6 lg:px-8">
                <p class="text-center text-base text-gray-400">
                    &copy; 2024 NotifyHub. All rights reserved.
                </p>
            </div>
        </footer>
    </div>
</body>

</html>
