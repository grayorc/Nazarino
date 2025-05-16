<?php

namespace Database\Seeders;

use App\Models\SubscriptionTier;
use App\Models\SubFeature;
use Illuminate\Database\Seeder;

class SubscriptionTierSubFeatureSeeder extends Seeder
{
    public function run(): void
    {
        $basicTier = SubscriptionTier::where('title', 'پایه')->first();
        $basicFeatures = SubFeature::whereIn('key', [
            'unlimited_elections'
        ])->pluck('id');
        $basicTier->subFeatures()->attach($basicFeatures);

        $proTier = SubscriptionTier::where('title', 'حرفه‌ای')->first();
        $proFeatures = SubFeature::whereIn('key', [
            'unlimited_elections',
            'charts',
            'exel_export'
        ])->pluck('id');
        $proTier->subFeatures()->attach($proFeatures);

        $enterpriseTier = SubscriptionTier::where('title', 'سازمانی')->first();
        $enterpriseFeatures = SubFeature::whereIn('key', [
            'unlimited_elections',
            'charts',
            'exel_export',
            'private_elections',
            'invite_to_election',
            'ai_analysis'
        ])->pluck('id');
        $enterpriseTier->subFeatures()->attach($enterpriseFeatures);
    }
}
