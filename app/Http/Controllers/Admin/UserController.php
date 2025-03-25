<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('id', $search);
            });
        }

        if ($request->input('admin') == 'on') {
            $query->where(function($q) {
                $q->where('is_staff', 1)
                    ->orWhere('is_superuser', 1);
            });
        }

        $users = $query->paginate(20);

        return view('admin.users.all', compact('users'))
            ->fragmentIf(request()->hasHeader('HX-Request'),'table-section');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255','unique:users'],
            'email' => ['required', 'email','max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8','max:255','confirmed'],
        ]);
        $user = User::create($data);
        if($request->has('activateEmail')){
            $user->markEmailAsVerified();
        }

    }

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
    public function edit(Request $request)
    {
        //
        $user = $request->user();
        return view('admin.users.edit');
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
    public function destroy(User $user)
    {
        $user->delete();
    }
}
