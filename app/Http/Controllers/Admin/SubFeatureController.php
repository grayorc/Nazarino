<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubFeature;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SubFeatureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = SubFeature::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('key', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%')
                    ->orWhere('id', $search);
            });
        }

        $subFeatures = $query->paginate(10);

        return view('admin.subfeatures.all', compact('subFeatures'))->fragment(request()->hasHeader('HX-Request') ? 'table-section' : '');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.subfeatures.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'key' => ['required', 'string', 'max:255', 'unique:sub_features'],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        SubFeature::create($data);

        return redirect(route('admin.subfeatures.index'))->with('success', 'ویژگی با موفقیت ایجاد شد');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SubFeature $subfeature)
    {
        return view('admin.subfeatures.edit', compact('subfeature'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SubFeature $subfeature)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        $subfeature->update($data);

        return redirect(route('admin.subfeatures.index'))->with('success', 'ویژگی با موفقیت بروزرسانی شد');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SubFeature $subfeature)
    {
        $subfeature->subscriptionTiers()->detach();
        $subfeature->delete();

        return response('', 200);
    }
}
