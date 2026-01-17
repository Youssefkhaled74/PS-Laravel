<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\VendorNotification;
use App\Models\Vendor;
use Carbon\Carbon;

class VendorNotificationSeeder extends Seeder
{
    public function run(): void
    {
        $vendor = Vendor::find(22);
        if (!$vendor) {
            return;
        }

        $now = Carbon::now();

        $samples = [
            // Today
            [
                'type' => 'order_shipped',
                'icon' => 'truck',
                'title' => ['en' => 'Order shipped confirmation', 'ar' => 'تأكيد شحن الطلب'],
                'body' => ['en' => 'Your order has been shipped by Sarah Bonyak - Product #12584', 'ar' => 'تم شحن طلبك من سارة بونياك - رقم المنتج #12584'],
                'data' => ['order_id' => 12584],
                'created_at' => $now->copy()->subHours(2),
            ],
            [
                'type' => 'rating_request',
                'icon' => 'star',
                'title' => ['en' => 'Rate your recent order', 'ar' => 'قيم طلبك الأخير'],
                'body' => ['en' => 'Please rate your purchase from Sarah Bonyak', 'ar' => 'يرجى تقييم مشترياتك من سارة بونياك'],
                'data' => ['order_id' => 12584],
                'created_at' => $now->copy()->subHours(3),
            ],

            // Yesterday
            [
                'type' => 'order_shipped',
                'icon' => 'truck',
                'title' => ['en' => 'Order shipped confirmation', 'ar' => 'تأكيد شحن الطلب'],
                'body' => ['en' => 'Your order has been shipped by Sarah Bonyak - Product #12585', 'ar' => 'تم شحن طلبك من سارة بونياك - رقم المنتج #12585'],
                'data' => ['order_id' => 12585],
                'created_at' => $now->copy()->subDay()->subHours(2),
            ],
            [
                'type' => 'rating_request',
                'icon' => 'star',
                'title' => ['en' => 'Rate your recent order', 'ar' => 'قيم طلبك الأخير'],
                'body' => ['en' => 'Please rate your purchase from Sarah Bonyak', 'ar' => 'يرجى تقييم مشترياتك من سارة بونياك'],
                'data' => ['order_id' => 12585],
                'created_at' => $now->copy()->subDay()->subHours(5),
            ],

            // Older
            [
                'type' => 'system',
                'icon' => 'info',
                'title' => ['en' => 'Welcome to PS', 'ar' => 'مرحباً بك في PS'],
                'body' => ['en' => 'We are happy to have you on board.', 'ar' => 'نحن سعداء بانضمامك إلينا.'],
                'data' => null,
                'created_at' => $now->copy()->subDays(10),
            ],
            [
                'type' => 'system',
                'icon' => 'info',
                'title' => ['en' => 'Platform update', 'ar' => 'تحديث النظام'],
                'body' => ['en' => 'New features are available in your dashboard.', 'ar' => 'ميزات جديدة متاحة في لوحة التحكم الخاصة بك.'],
                'data' => null,
                'created_at' => $now->copy()->subDays(30),
            ],
        ];

        foreach ($samples as $item) {
            VendorNotification::create(array_merge($item, ['vendor_id' => $vendor->id]));
        }
    }
}
