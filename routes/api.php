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

// Vendor API routes
Route::prefix('v1/vendor/auth')->group(function () {
    require __DIR__ . '/api/vendor/auth.php';
});
