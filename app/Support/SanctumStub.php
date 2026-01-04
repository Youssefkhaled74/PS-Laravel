<?php

// Development stub to satisfy static analysis when laravel/sanctum is not installed.
// Remove this file when you install Sanctum via Composer.

namespace Laravel\Sanctum;

if (! trait_exists(\Laravel\Sanctum\HasApiTokens::class)) {
    trait HasApiTokens
    {
        /**
         * Minimal fallback createToken implementation for development only.
         * Returns an object with `plainTextToken` property.
         */
        public function createToken(string $name, array $abilities = ['*'])
        {
            $token = bin2hex(random_bytes(32));
            return (object) ['plainTextToken' => $token];
        }
    }
}
