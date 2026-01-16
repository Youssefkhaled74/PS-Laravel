<?php

namespace App\Services\Admin;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Models\User;
use App\Models\Vendor;
use App\Services\Auth\OtpService as AuthOtpService;

class OtpService
{
    public function __construct(protected ?AuthOtpService $authOtp = null)
    {
    }

    public function paginate(array $filters = [], int $perPage = 15)
    {
        $query = DB::table('otps')->orderByDesc('id');

        if (!empty($filters['search'])) {
            $s = $filters['search'];
            $query->where(function ($q) use ($s) {
                $q->where('phone', 'like', "%{$s}%")
                  ->orWhere('country_code', 'like', "%{$s}%")
                  ->orWhere('purpose', 'like', "%{$s}%");
            });
        }

        if (!empty($filters['purpose'])) {
            $query->where('purpose', $filters['purpose']);
        }
        if (!empty($filters['channel'])) {
            // channel not present in table by default; keep for future
        }
        if (!empty($filters['status'])) {
            $status = $filters['status'];
            $query->where(function ($q) use ($status) {
                $now = Carbon::now();
                if ($status === 'used') {
                    $q->whereNotNull('verified_at');
                } elseif ($status === 'revoked') {
                    $q->whereNotNull('revoked_at');
                } elseif ($status === 'expired') {
                    $q->where('expires_at', '<', $now);
                } elseif ($status === 'active') {
                    $q->whereNull('verified_at')->whereNull('revoked_at')->where('expires_at', '>', $now);
                }
            });
        }

        // Fetch user otps
        $userItems = $query->get()->map(function ($row) {
            $row->source = 'user';
            $row->user = User::where('country_code', $row->country_code)->where('phone', $row->phone)->first();
            $row->vendor = Vendor::where('phone', $row->phone)->first();
            // ensure purpose property exists
            $row->purpose = $row->purpose ?? null;
            $row->status = $this->computeStatus($row);
            // include cached plain OTP if debug
            if (config('app.otp_debug', false)) {
                $row->plain_otp = Cache::get('otp_plain_' . $row->id);
            }
            return $row;
        });

        // Fetch vendor otps and map to common structure
        $vQuery = DB::table('vendor_otps')->orderByDesc('id');
        if (!empty($filters['search'])) {
            $s = $filters['search'];
            $vQuery->where(function ($q) use ($s) {
                $q->where('phone', 'like', "%{$s}%")
                  ->orWhere('purpose', 'like', "%{$s}%");
            });
        }
        if (!empty($filters['purpose'])) {
            $vQuery->where('purpose', $filters['purpose']);
        }

        $vendorItems = $vQuery->get()->map(function ($row) {
            // normalize fields to match otps table shape
            $row->source = 'vendor';
            $row->country_code = $row->country_code ?? null;
            $row->user = null;
            $row->vendor = Vendor::find($row->vendor_id);
            $row->purpose = $row->purpose ?? null;
            // map consumed_at -> verified_at for status computation
            $row->verified_at = $row->consumed_at ?? null;
            $row->revoked_at = $row->revoked_at ?? null;
            $row->status = $this->computeStatus($row);
            if (config('app.otp_debug', false)) {
                $row->plain_otp = Cache::get('otp_plain_' . $row->id);
            }
            return $row;
        });

        // Merge and sort by created_at desc
        $all = $userItems->concat($vendorItems)->sortByDesc(fn($r) => $r->created_at)->values();

        // Manual pagination
        $page = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage();
        $total = $all->count();
        $results = $all->slice(($page - 1) * $perPage, $perPage)->values();
        $paginator = new \Illuminate\Pagination\LengthAwarePaginator($results, $total, $perPage, $page, [
            'path' => \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPath(),
            'query' => request()->query(),
        ]);

        return $paginator;
    }

    public function getOtp(int $id)
    {
        $row = DB::table('otps')->where('id', $id)->first();
        if (! $row) return null;
        // attach user if exists by phone
        $user = User::where('country_code', $row->country_code)->where('phone', $row->phone)->first();
        $row->user = $user;
        $row->status = $this->computeStatus($row);
        // expose plain OTP in debug mode if available in cache
        if (config('app.otp_debug', false)) {
            $plain = Cache::get('otp_plain_' . $row->id);
            $row->plain_otp = $plain;
        }
        return $row;
    }

    public function getVendorOtp(int $id)
    {
        $row = DB::table('vendor_otps')->where('id', $id)->first();
        if (! $row) return null;
        $row->vendor = Vendor::find($row->vendor_id);
        $row->purpose = $row->purpose ?? null;
        // map consumed_at to verified_at for status logic
        $row->verified_at = $row->consumed_at ?? null;
        $row->revoked_at = $row->revoked_at ?? null;
        $row->status = $this->computeStatus($row);
        if (config('app.otp_debug', false)) {
            $row->plain_otp = Cache::get('otp_plain_' . $row->id);
        }
        return $row;
    }

    public function computeStatus($row): string
    {
        $now = Carbon::now();
        if (!empty($row->verified_at)) return 'used';
        if (!empty($row->revoked_at)) return 'revoked';
        if ($now->greaterThan(Carbon::parse($row->expires_at))) return 'expired';
        return 'active';
    }

    public function revoke(int $id, ?string $reason = null, int $adminId = null): bool
    {
        $row = DB::table('otps')->where('id', $id)->first();
        if (! $row) return false;
        if (! empty($row->revoked_at)) return false;
        DB::table('otps')->where('id', $id)->update(['revoked_at' => Carbon::now(), 'updated_at' => Carbon::now()]);
        // log admin action if adminId present
        if ($adminId) {
            DB::table('admin_activity_logs')->insert([
                'admin_id' => $adminId,
                'action' => 'otp_revoked',
                'details' => json_encode(['otp_id' => $id, 'reason' => $reason]),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
        return true;
    }

    public function delete(int $id, ?int $adminId = null): bool
    {
        $row = DB::table('otps')->where('id', $id)->first();
        if (! $row) return false;
        DB::table('otps')->where('id', $id)->delete();
        if ($adminId) {
            DB::table('admin_activity_logs')->insert([
                'admin_id' => $adminId,
                'action' => 'otp_deleted',
                'details' => json_encode(['otp_id' => $id]),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
        return true;
    }

    public function resend(int $id): array
    {
        // if AuthOtpService provided, create a new OTP for same phone/purpose
        $row = DB::table('otps')->where('id', $id)->first();
        if (! $row) return ['ok' => false, 'reason' => 'not_found'];
        if (! $this->authOtp) return ['ok' => false, 'reason' => 'not_supported'];
        return $this->authOtp->generateOtp($row->country_code ?? '+966', $row->phone, $row->purpose);
    }
}
