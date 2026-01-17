<?php

use Illuminate\Support\Facades\Route;

/**
 * NOTE:
 * - This file must contain ONLY ONE opening PHP tag.
 * - Do NOT add another "<?php" later in the file.
 * - Laravel already applies the "api" middleware group to routes/api.php by default.
 */

// Legacy (kept for backward compatibility)
require __DIR__ . '/api/auth.php';
require __DIR__ . '/api/otp.php';
require __DIR__ . '/api/password.php';
require __DIR__ . '/api/address.php';

// New organized user API route files
require __DIR__ . '/api/user_auth.php';
require __DIR__ . '/api/user_otp.php';
require __DIR__ . '/api/user_password.php';
require __DIR__ . '/api/user_profile.php';
require __DIR__ . '/api/user_addresses.php';
require __DIR__ . '/api/stories.php';
require __DIR__ . '/api/user/follows.php';
// User v1 APIs
Route::prefix('v1')->group(function () {
    require __DIR__ . '/api/user/cart.php';
});

// Vendor API routes
Route::prefix('v1/vendor')->group(function () {
    // Auth routes
    Route::prefix('auth')->group(function () {
        require __DIR__ . '/api/vendor/auth.php';
    });
    
    // OTP routes
    Route::prefix('otp')->group(function () {
        require __DIR__ . '/api/vendor/otp.php';
    });
    
    // Onboarding routes
    Route::prefix('onboarding')->group(function () {
        require __DIR__ . '/api/vendor/onboarding.php';
    });
    
    // Packages routes
    Route::prefix('packages')->group(function () {
        require __DIR__ . '/api/vendor/packages.php';
    });
    
    // Payment routes
    Route::prefix('payment')->group(function () {
        require __DIR__ . '/api/vendor/payment.php';
    });
    
    // Profile routes
    require __DIR__ . '/api/vendor/me.php';
    
    // Items routes
    require __DIR__ . '/api/vendor/items.php';

    // Notifications routes
    require __DIR__ . '/api/vendor/notifications.php';
    require __DIR__ . '/api/vendor/followers.php';
    require __DIR__ . '/api/vendor/stories.php';
    require __DIR__ . '/api/vendor/shipping.php';
});
