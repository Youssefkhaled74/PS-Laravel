<?php

namespace App\Http\Controllers\Api\V1\User\Checkout;

use App\Http\Controllers\Controller;
use App\Services\User\Checkout\CheckoutService;
use App\Traits\ApiResponseTrait;

class CheckoutSummaryController extends Controller
{
    use ApiResponseTrait;

    protected CheckoutService $service;

    public function __construct(CheckoutService $service)
    {
        $this->service = $service;
    }

    public function show()
    {
        $user = request()->user();
        $summary = $this->service->summary($user);
        return $this->success($summary, 'checkout.summary.loaded');
    }
}
