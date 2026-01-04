<?php

namespace App\Services;

use App\Models\Address;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class AddressService
{
    public function listForUser($user): Collection
    {
        return Address::where('user_id', $user->id)->get();
    }

    public function findForUser($user, int $id): ?Address
    {
        return Address::where('user_id', $user->id)->where('id', $id)->first();
    }

    public function createForUser($user, array $data): Address
    {
        return DB::transaction(function () use ($user, $data) {
            if (! empty($data['is_default'])) {
                // unset previous defaults
                Address::where('user_id', $user->id)->where('is_default', true)->update(['is_default' => false]);
            }

            $address = new Address($data);
            $address->user_id = $user->id;
            $address->save();

            return $address;
        });
    }

    public function updateForUser($user, Address $address, array $data): Address
    {
        return DB::transaction(function () use ($user, $address, $data) {
            if (! $address || $address->user_id !== $user->id) {
                throw new \RuntimeException('forbidden');
            }

            if (array_key_exists('is_default', $data) && $data['is_default']) {
                Address::where('user_id', $user->id)->where('is_default', true)->where('id', '!=', $address->id)->update(['is_default' => false]);
            }

            $address->fill($data);
            $address->save();

            return $address;
        });
    }

    public function deleteForUser($user, Address $address): bool
    {
        return DB::transaction(function () use ($user, $address) {
            if (! $address || $address->user_id !== $user->id) {
                throw new \RuntimeException('forbidden');
            }

            $wasDefault = (bool) $address->is_default;
            $address->delete();

            if ($wasDefault) {
                // set another address as default if exists: pick latest
                $next = Address::where('user_id', $user->id)->latest('id')->first();
                if ($next) {
                    $next->is_default = true;
                    $next->save();
                }
            }

            return true;
        });
    }

    public function setDefault($user, Address $address): Address
    {
        return DB::transaction(function () use ($user, $address) {
            if (! $address || $address->user_id !== $user->id) {
                throw new \RuntimeException('forbidden');
            }

            Address::where('user_id', $user->id)->where('is_default', true)->update(['is_default' => false]);
            $address->is_default = true;
            $address->save();

            return $address;
        });
    }
}
