<?php

namespace App\Http\Controllers\Api\Adresses;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAddressRequest;
use App\Http\Requests\UpdateAddressRequest;
use Illuminate\Http\Request;
use App\Http\Resources\AddressResource;
use App\Services\AddressService;
use App\Traits\ApiResponseTrait;

class AddressController extends Controller
{
    use ApiResponseTrait;

    protected AddressService $service;

    public function __construct(AddressService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $list = $this->service->listForUser($user);
        return $this->success(AddressResource::collection($list), 'success');
    }

    public function store(StoreAddressRequest $request)
    {
        $user = $request->user();
        $data = $request->validated();
        $address = $this->service->createForUser($user, $data);
        return $this->success(new AddressResource($address), 'address_created', null, 201);
    }

    public function show(Request $request, $id)
    {
        $user = $request->user();
        $address = $this->service->findForUser($user, (int)$id);
        if (! $address) return $this->error('address_not_found', null, 404);
        return $this->success(new AddressResource($address), 'success');
    }

    public function update(UpdateAddressRequest $request, $id)
    {
        $user = $request->user();
        $address = $this->service->findForUser($user, (int)$id);
        if (! $address) return $this->error('address_not_found', null, 404);
        $updated = $this->service->updateForUser($user, $address, $request->validated());
        return $this->success(new AddressResource($updated), 'address_updated');
    }

    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        $address = $this->service->findForUser($user, (int)$id);
        if (! $address) return $this->error('address_not_found', null, 404);
        $this->service->deleteForUser($user, $address);
        return $this->success(null, 'address_deleted');
    }

    public function setDefault(Request $request, $id)
    {
        $user = $request->user();
        $address = $this->service->findForUser($user, (int)$id);
        if (! $address) return $this->error('address_not_found', null, 404);
        $addr = $this->service->setDefault($user, $address);
        return $this->success(new AddressResource($addr), 'address_set_default');
    }
}
