<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Election;
use Illuminate\Http\Request;

class ElectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Election::query();

        if ($request->has('search') && !empty($request->input('search'))) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        if ($request->has('filter')) {
            switch ($request->input('filter')) {
                case 'visible':
                    $query->where('is_public', true);
                    break;
                case 'hidden':
                    $query->where('is_public', false);
                    break;
                case 'all':
                    break;
            }
        }

        if ($request->has('status')) {
            switch ($request->input('status')) {
                case 'open':
                    $query->where('is_open', true);
                    break;
                case 'closed':
                    $query->where('is_open', false);
                    break;
            }
        }

        $elections = $query->paginate(10);

        $elections->appends($request->all());

        return view('admin.elections.all', compact('elections'))->fragment(request()->hasHeader('HX-Request') ? 'table-section' : '');
    }

    // Create and store methods removed

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Election $election)
    {
        $election->delete();
        
        return response('', 200);
    }
}
