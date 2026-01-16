<?php

namespace App\Services\Vendor\Auth;

use App\Models\Vendor;
use App\Traits\UploadsTrait;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class VendorAuthService
{
    use UploadsTrait;

    /**
     * Register a new vendor
     *
     * @param array $data
     * @return array ['vendor' => Vendor, 'token' => string]
     */
    public function register(array $data): array
    {
        DB::beginTransaction();
        try {
            // Handle avatar upload if provided
            $avatarPath = null;
            if (isset($data['avatar'])) {
                $avatarPath = $this->uploadImage(
                    $data['avatar'],
                    'uploads/vendor/avatars'
                );
            }

            // Prepare vendor data
            $vendorData = [
                'full_name' => $data['full_name'],
                // `name` column is required in DB; use provided `name` or fallback to `full_name`
                'name' => $data['name'] ?? $data['full_name'],
                'phone' => $data['phone'],
                'password' => Hash::make($data['password']),
                'status' => 'pending', // Default status
                'email' => $data['email'] ?? null,
                'second_phone' => $data['second_phone'] ?? null,
                'bio' => $data['bio'] ?? null,
                'national_id' => $data['national_id'] ?? null,
                'national_address' => $data['national_address'] ?? null,
                'lat' => $data['lat'] ?? null,
                'lng' => $data['lng'] ?? null,
                'avatar_path' => $avatarPath,
            ];

            // Create vendor
            $vendor = Vendor::create($vendorData);

            // Generate token
            $token = $vendor->createToken('vendor-token')->plainTextToken;

            DB::commit();

            return [
                'vendor' => $vendor->fresh(),
                'token' => $token,
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Login vendor with phone and password
     *
     * @param array $data
     * @return array ['vendor' => Vendor, 'token' => string]
     * @throws \Exception
     */
    public function login(array $data): array
    {
        $vendor = Vendor::where('phone', $data['phone'])->first();

        if (!$vendor || !Hash::check($data['password'], $vendor->password)) {
            throw new \Exception('Invalid credentials');
        }

        // Generate token
        $token = $vendor->createToken('vendor-token')->plainTextToken;

        return [
            'vendor' => $vendor,
            'token' => $token,
        ];
    }

    /**
     * Logout vendor (revoke current token)
     *
     * @param Vendor $vendor
     * @return void
     */
    public function logout(Vendor $vendor): void
    {
        // Revoke current token
        $vendor->tokens()->delete();
    }
}
