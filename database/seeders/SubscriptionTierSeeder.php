<?php

namespace Database\Seeders;

use App\Models\SubscriptionTier;
use Illuminate\Database\Seeder;

class SubscriptionTierSeeder extends Seeder
{
    public function run(): void
    {
        $tiers = [
            [
                'title' => 'پایه',
                'price' => 99000.00,
            ],
            [
                'title' => 'حرفه‌ای',
                'price' => 199000.00,
            ],
            [
                'title' => 'سازمانی',
                'price' => 499000.00,
            ],
        ];

        foreach ($tiers as $tier) {
            SubscriptionTier::create($tier);
        }
    }
}
