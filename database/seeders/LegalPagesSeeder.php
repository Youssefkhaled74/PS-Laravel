<?php

namespace Database\Seeders;

use App\Models\LegalPage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LegalPagesSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $now = now();

        LegalPage::updateOrCreate([
            'key' => 'terms'
        ], [
            'title' => ['en' => 'Terms & Conditions', 'ar' => 'الشروط والأحكام'],
            'content' => [
                'en' => "## Terms & Conditions\n\n- Welcome to PS.\n- Use the app according to these rules.\n\nThese terms govern use of the service.",
                'ar' => "## الشروط والأحكام\n\n- مرحبًا بكم في PS.\n- استخدم التطبيق وفقًا لهذه القواعد.\n\nهذه الشروط تنظم استخدام الخدمة."
            ],
            'status' => 'published',
            'version' => 1,
            'updated_by_admin_id' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        LegalPage::updateOrCreate([
            'key' => 'privacy'
        ], [
            'title' => ['en' => 'Privacy Policy', 'ar' => 'سياسة الخصوصية'],
            'content' => [
                'en' => "## Privacy Policy\n\n- We respect your privacy.\n- We do not sell personal data.\n\nContact support for privacy requests.",
                'ar' => "## سياسة الخصوصية\n\n- نحن نحترم خصوصيتك.\n- لا نقوم ببيع البيانات الشخصية.\n\nاتصل بالدعم لطلبات الخصوصية."
            ],
            'status' => 'published',
            'version' => 1,
            'updated_by_admin_id' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }
}
