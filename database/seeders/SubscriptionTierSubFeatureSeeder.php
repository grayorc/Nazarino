<?php

namespace Database\Seeders;

use App\Models\SubscriptionTier;
use App\Models\SubFeature;
use Illuminate\Database\Seeder;

class SubscriptionTierSubFeatureSeeder extends Seeder
{
    public function run(): void
    {
        // پلن پایه
        $basicTier = SubscriptionTier::where('title', 'پایه')->first();
        $basicFeatures = SubFeature::whereIn('key', [
            'unlimited_access'
        ])->pluck('id');
        $basicTier->subFeatures()->attach($basicFeatures);

        // پلن حرفه‌ای
        $proTier = SubscriptionTier::where('title', 'حرفه‌ای')->first();
        $proFeatures = SubFeature::whereIn('key', [
            'unlimited_access',
            'charts',
            'exel_export'
        ])->pluck('id');
        $proTier->subFeatures()->attach($proFeatures);

        // پلن سازمانی
        $enterpriseTier = SubscriptionTier::where('title', 'سازمانی')->first();
        $enterpriseFeatures = SubFeature::whereIn('key', [
            'unlimited_access',
            'charts',
            'exel_export',
            'private_elections',
            'invite_to_election'
        ])->pluck('id');
        $enterpriseTier->subFeatures()->attach($enterpriseFeatures);
    }
}
