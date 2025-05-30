<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Election;
use App\Models\SubscriptionTier;
use App\Models\SubscriptionUser;
use App\Models\User;
use App\Models\Vote;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function index()
    {
        $stats = [
            'elections_count' => Election::count(),
            'votes_count' => Vote::count(),
            'users_count' => User::count(),
            'active_subscriptions_count' => SubscriptionUser::where('status', 'active')->count()
        ];

        $latestElections = Election::latest()->take(7)->get();
        $elections = [];

        foreach ($latestElections as $election) {
            $voteCount = 0;
            $options = $election->options;

            foreach ($options as $option) {
                $voteCount += $option->votes->count();
            }

            $status = $election->is_open ? 'success' : 'danger';
            $statusText = $election->is_open ? 'فعال' : 'بسته شده';

            if ($election->end_date && Carbon::parse($election->end_date)->isPast()) {
                $status = 'danger';
                $statusText = 'بسته شده';
            }

            $elections[] = [
                'id' => $election->id,
                'title' => Str::limit($election->title, 30),
                'status' => $status,
                'status_text' => $statusText,
                'vote_count' => $voteCount
            ];
        }

        $recentUsers = User::latest()->take(5)->get();

        $subscriptionTiers = SubscriptionTier::all();
        //count users having active subscriptions
        foreach ($subscriptionTiers as $tier) {
            $tier->subscription_users_count = SubscriptionUser::where('subscription_tier_id', $tier->id)
                ->where('status', 'active')
                ->count();
        }

        $subscriptionTiers = $subscriptionTiers->sortByDesc('subscription_users_count')->values();

        $votesData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $count = Vote::whereDate('created_at', $date->format('Y-m-d'))->count();
            $votesData[] = [
                'date' => $date->format('Y-m-d'),
                'count' => $count
            ];
        }

        $publicElections = Election::where('is_public', true)->count();
        $privateElections = Election::where('is_public', false)->count();

        $electionTypes = [
            ['label' => 'نظرسنجی عمومی', 'count' => $publicElections],
            ['label' => 'نظرسنجی خصوصی', 'count' => $privateElections]
        ];

        return view('admin.index', compact(
            'stats',
            'elections',
            'recentUsers',
            'subscriptionTiers',
            'votesData',
            'electionTypes'
        ));
    }
}
