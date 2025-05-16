<?php

namespace Database\Seeders;

use App\Models\SubFeature;
use Illuminate\Database\Seeder;

class SubFeatureSeeder extends Seeder
{
    public function run(): void
    {
        $subFeatures = [
            [
                'name' => 'نظرسنجی نامحدود',
                'description' => 'امکان ساخت تعداد نامحدود نظرسنجی',
                'key' => 'unlimited_elections',
            ],
            [
                'name' => 'نمودار ها',
                'description' => 'امکان استفاده از انواع نمودار',
                'key' => 'charts',
            ],
            [
                'name' => 'خروجی اکسل',
                'description' => 'امکان خروجی گرفتن به صورت اکسل',
                'key' => 'exel_export',
            ],
            [
                'name' => 'نظرسنجی خصوصی',
                'description' => 'امکان ساخت نظرسنجی خصوصی',
                'key' => 'private_elections',
            ],
            [
                'name' => 'دعوت به نظرسنجی',
                'description' => 'امکان دعوت افراد به نظرسنجی',
                'key' => 'invite_to_election',
            ],
            [
                'name' => 'تحلیل هوش مصنوعی',
                'description' => 'امکان استفاده از هوش مصنوعی در نظرسنجی',
                'key' => 'ai_analysis',
            ],
        ];

        foreach ($subFeatures as $feature) {
            SubFeature::create($feature);
        }
    }
}
