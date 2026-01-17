<?php

namespace App\Http\Controllers\Api\V1\User\Checkout;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\User\Checkout\CheckoutPaymentRequest;
use App\Services\User\Checkout\CheckoutService;
use App\Traits\ApiResponseTrait;
use App\Http\Resources\Api\V1\Vendor\PaymentMethodResource;
use App\Models\PaymentMethod;

class CheckoutPaymentController extends Controller
{
    use ApiResponseTrait;

    protected CheckoutService $service;

    public function __construct(CheckoutService $service)
    {
        $this->service = $service;
    }

    public function methods()
    {
        $methods = PaymentMethod::active()->ordered()->get();
        return $this->success(PaymentMethodResource::collection($methods), 'success');
    }

    public function select(CheckoutPaymentRequest $request)
    {
        $user = $request->user();
        $this->service->selectPaymentMethod($user, $request->validated()['payment_method_id']);
        return $this->success(null, 'checkout.payment.selected');
    }
}
