<?php

/**
 * NOTE:
 * - This file must contain ONLY ONE opening PHP tag.
 * - Do NOT add another "<?php" later in the file.
 * - Laravel already applies the "api" middleware group to routes/api.php by default.
 */

require __DIR__ . '/api/auth.php';
require __DIR__ . '/api/otp.php';
require __DIR__ . '/api/password.php';
require __DIR__ . '/api/address.php';
