<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    use ApiResponseTrait;

    public function logout(Request $request)
    {
        $user = $request->user();
        if ($user) {
            // revoke current access token
            if (method_exists($user, 'currentAccessToken')) {
                $token = $user->currentAccessToken();
                if ($token) $token->delete();
            } else {
                // fallback: delete all tokens
                if (method_exists($user, 'tokens')) {
                    $user->tokens()->delete();
                }
            }
        }

        return $this->success(null, 'logout_success');
    }
}
