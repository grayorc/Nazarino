<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubFeature;
use App\Models\SubscriptionTier;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SubscriptionTierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = SubscriptionTier::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('price', 'like', '%' . $search . '%')
                    ->orWhere('id', $search);
            });
        }

        $subscriptionTiers = $query->paginate(10);

        if (request()->hasHeader('HX-Request')) {
            return view('admin.subscription-tiers.all', compact('subscriptionTiers'))->fragment('table-section');
        }

        return view('admin.subscription-tiers.all', compact('subscriptionTiers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $subFeatures = SubFeature::all();
        return view('admin.subscription-tiers.create', compact('subFeatures'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'sub_features' => ['array'],
        ]);

        $subscriptionTier = SubscriptionTier::create([
            'title' => $data['title'],
            'price' => $data['price'],
        ]);

        if(isset($data['sub_features'])) {
            $subscriptionTier->subFeatures()->sync($data['sub_features']);
        }

        return redirect(route('admin.subscription-tiers.index'))->with('success', 'سطح اشتراک با موفقیت ایجاد شد');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SubscriptionTier $subscriptionTier)
    {
        $subFeatures = SubFeature::all();
        $subscriptionTier->load('subFeatures');
        return view('admin.subscription-tiers.edit', compact('subscriptionTier', 'subFeatures'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SubscriptionTier $subscriptionTier)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'sub_features' => ['array'],
        ]);

        $subscriptionTier->update([
            'title' => $data['title'],
            'price' => $data['price'],
        ]);

        if(isset($data['sub_features'])) {
            $subscriptionTier->subFeatures()->sync($data['sub_features']);
        } else {
            $subscriptionTier->subFeatures()->detach();
        }

        return redirect(route('admin.subscription-tiers.index'))->with('success', 'سطح اشتراک با موفقیت بروزرسانی شد');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SubscriptionTier $subscriptionTier)
    {
        $subscriptionTier->subFeatures()->detach();
        $subscriptionTier->delete();
        
        return response('', 200);
    }
}
