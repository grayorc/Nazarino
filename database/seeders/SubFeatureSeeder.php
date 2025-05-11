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
                'name' => 'دسترسی نامحدود',
                'description' => 'دسترسی به تمام امکانات نظرینو',
                'key' => 'unlimited_access',
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
        ];

        foreach ($subFeatures as $feature) {
            SubFeature::create($feature);
        }
    }
}
