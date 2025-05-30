<?php

namespace App\Http\Controllers\Admin;

use App\Exports\PermissionExport;
use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Permission::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('display_name', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%')
                    ->orWhere('id', $search);
            });
        }

        $permissions = $query->paginate(10);

        if (request()->hasHeader('HX-Request')) {
            return view('admin.permissions.all', compact('permissions'))->fragment('table-section');
        }

        return view('admin.permissions.all', compact('permissions'));
    }
    
    /**
     * Export permissions to Excel
     */
    public function export(Request $request)
    {
        // Build the same query as index to maintain filter consistency
        $query = Permission::query()->with('roles');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('display_name', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%')
                    ->orWhere('id', $search);
            });
        }

        $permissions = $query->get();
        
        return Excel::download(new PermissionExport($permissions), 'permissions-' . now()->format('Y-m-d') . '.xlsx');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.permissions.create');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Permission $permission)
    {
        return view('admin.permissions.edit', compact('permission'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Permission $permission)
    {
        $data = $request->validate([
            'display_name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        $permission->update([
            'display_name' => $data['display_name'],
            'description' => $data['description'] ?? null,
        ]);

        return redirect(route('admin.permissions.index'))->with('success', 'دسترسی با موفقیت بروزرسانی شد');
    }
}
