<?php

namespace App\Http\Controllers\Admin;

use App\Exports\SubscriptionUserExport;
use App\Http\Controllers\Controller;
use App\Models\SubscriptionTier;
use App\Models\SubscriptionUser;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class SubscriptionUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = SubscriptionUser::with(['user', 'subscriptionTier']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('id', $search)
                    ->orWhereHas('user', function($userQuery) use ($search) {
                        $userQuery->where('username', 'like', '%' . $search . '%')
                            ->orWhere('email', 'like', '%' . $search . '%')
                            ->orWhere('first_name', 'like', '%' . $search . '%')
                            ->orWhere('last_name', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('subscriptionTier', function($tierQuery) use ($search) {
                        $tierQuery->where('title', 'like', '%' . $search . '%');
                    });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $subscriptionUsers = $query->paginate(10);

        return view('admin.subscription-users.all', compact('subscriptionUsers'))->fragment(request()->hasHeader('HX-Request') ? 'table-section' : '');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $users = User::all();
        $subscriptionTiers = SubscriptionTier::all();
        $selectedUserId = $request->input('user_id');

        return view('admin.subscription-users.create', compact('users', 'subscriptionTiers', 'selectedUserId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'subscription_tier_id' => ['required', 'exists:subscription_tiers,id'],
            'status' => ['required', 'in:active,inactive,pending,cancelled'],
            'duration' => ['required', 'integer', 'min:1'],
            'duration_unit' => ['required', 'in:days,months,years'],
        ]);

        $startsAt = Carbon::now();
        $endsAt = null;
        // dd($data['duration'], $data['duration_unit']);

        if ($data['duration_unit'] === 'days') {
            $endsAt = $startsAt->copy()->addDays(intval($data['duration']));
        } elseif ($data['duration_unit'] === 'months') {
            $endsAt = $startsAt->copy()->addMonths(intval($data['duration']));
        } elseif ($data['duration_unit'] === 'years') {
            $endsAt = $startsAt->copy()->addYears(intval($data['duration']));
        }
        SubscriptionUser::create([
            'user_id' => $data['user_id'],
            'subscription_tier_id' => $data['subscription_tier_id'],
            'status' => $data['status'],
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
        ]);

        return redirect(route('admin.subscription-users.index'))->with('success', 'اشتراک کاربر با موفقیت ایجاد شد');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SubscriptionUser $subscriptionUser)
    {
        $users = User::all();
        $subscriptionTiers = SubscriptionTier::all();
        return view('admin.subscription-users.edit', compact('subscriptionUser', 'users', 'subscriptionTiers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SubscriptionUser $subscriptionUser)
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'subscription_tier_id' => ['required', 'exists:subscription_tiers,id'],
            'status' => ['required', 'in:active,inactive,pending,cancelled'],
            'starts_at' => ['required', 'date'],
            'ends_at' => ['required', 'date', 'after:starts_at'],
        ]);

        $subscriptionUser->update($data);

        return redirect(route('admin.subscription-users.index'))->with('success', 'اشتراک کاربر با موفقیت بروزرسانی شد');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SubscriptionUser $subscriptionUser)
    {
        $subscriptionUser->delete();

        return response('', 200);
    }
    
    /**
     * Export subscription users to Excel
     */
    public function export(Request $request)
    {
        // Build the same query as index to maintain filter consistency
        $query = SubscriptionUser::with(['user', 'subscriptionTier']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('id', $search)
                    ->orWhereHas('user', function($userQuery) use ($search) {
                        $userQuery->where('username', 'like', '%' . $search . '%')
                            ->orWhere('email', 'like', '%' . $search . '%')
                            ->orWhere('first_name', 'like', '%' . $search . '%')
                            ->orWhere('last_name', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('subscriptionTier', function($tierQuery) use ($search) {
                        $tierQuery->where('title', 'like', '%' . $search . '%');
                    });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $subscriptionUsers = $query->get();
        
        return Excel::download(new SubscriptionUserExport($subscriptionUsers), 'subscription-users-' . now()->format('Y-m-d') . '.xlsx');
    }
}
