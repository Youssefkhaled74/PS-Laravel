<?php

namespace App\Http\Controllers\Api\User\Profile;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponseTrait;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class MeController extends Controller
{
    use ApiResponseTrait;

    public function me(Request $request)
    {
        $user = $request->user();
        return $this->success(new UserResource($user), 'success');
    }
}
