<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\VendorPackage;

class VendorPackageSeeder extends Seeder
{
    public function run()
    {
        $packages = [
            [
                'key' => 'primary',
                'name' => ['en' => 'Primary', 'ar' => 'الأساسية'],
                'monthly' => 9900,
                'yearly' => 118800,
                'sort' => 0,
                'features' => [
                    'en' => [
                        'Up to 50 products',
                        'Basic analytics',
                        'Email support',
                        'Mobile app access',
                    ],
                    'ar' => [
                        'حتى 50 منتج',
                        'تحليلات أساسية',
                        'دعم عبر البريد الإلكتروني',
                        'الوصول إلى تطبيق الجوال',
                    ],
                ],
            ],
            [
                'key' => 'basic',
                'name' => ['en' => 'Basic', 'ar' => 'الأساسية المحسنة'],
                'monthly' => 29900,
                'yearly' => 358800,
                'sort' => 1,
                'features' => [
                    'en' => [
                        'Up to 200 products',
                        'Advanced analytics',
                        'Priority email support',
                        'Social media integration',
                        'Custom branding',
                    ],
                    'ar' => [
                        'حتى 200 منتج',
                        'تحليلات متقدمة',
                        'دعم بريد إلكتروني ذو أولوية',
                        'التكامل مع وسائل التواصل',
                        'علامة تجارية مخصصة',
                    ],
                ],
            ],
            [
                'key' => 'advanced',
                'name' => ['en' => 'Advanced', 'ar' => 'المتقدمة'],
                'monthly' => 39900,
                'yearly' => 478800,
                'sort' => 2,
                'features' => [
                    'en' => [
                        'Unlimited products',
                        'Premium analytics & reports',
                        'Phone & email support',
                        'Marketing tools',
                        'API access',
                        'Multi-location support',
                    ],
                    'ar' => [
                        'منتجات غير محدودة',
                        'تحليلات وتقارير متقدمة',
                        'دعم هاتفي وبريد إلكتروني',
                        'أدوات التسويق',
                        'الوصول إلى API',
                        'دعم مواقع متعددة',
                    ],
                ],
            ],
            [
                'key' => 'professional',
                'name' => ['en' => 'Professional', 'ar' => 'الاحترافية'],
                'monthly' => 199900,
                'yearly' => 2398800,
                'sort' => 3,
                'features' => [
                    'en' => [
                        'Everything in Advanced',
                        'Dedicated account manager',
                        '24/7 priority support',
                        'Custom integrations',
                        'Advanced automation',
                        'Team collaboration tools',
                        'White-label options',
                    ],
                    'ar' => [
                        'كل ميزات المتقدمة',
                        'مدير حساب مخصص',
                        'دعم على مدار الساعة',
                        'تكاملات مخصصة',
                        'أتمتة متقدمة',
                        'أدوات تعاون الفريق',
                        'خيارات العلامة البيضاء',
                    ],
                ],
            ],
        ];

        foreach ($packages as $p) {
            VendorPackage::updateOrCreate(
                ['key' => $p['key']],
                [
                    'name' => $p['name'],
                    'monthly_price' => $p['monthly'],
                    'yearly_price' => $p['yearly'],
                    'currency' => 'SAR',
                    'features' => $p['features'],
                    'sort_order' => $p['sort'],
                    'status' => VendorPackage::STATUS_ACTIVE,
                ]
            );
        }
    }
}

