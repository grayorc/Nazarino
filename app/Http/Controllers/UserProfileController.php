<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Follower;
use App\Models\Election;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserProfileController extends Controller
{

    public function show(User $user)
    {
        $elections = $user->elections()
            ->where('is_public', true)
            ->with(['options', 'user'])
            ->latest()
            ->paginate(6);

        $isFollowing = false;
        if (auth()->check()) {
            $isFollowing = Follower::where('user_id', $user->id)
                ->where('follower_id', auth()->user()->id)
                ->exists();
        }

        $followersCount = $user->countFollowers();
        $followingsCount = $user->countFollowings();

        return view('users.profile', compact('user', 'elections', 'isFollowing', 'followersCount', 'followingsCount'));
    }

    public function follow(string $username)
    {
        $user = User::where('username', $username)->first();

        if (auth()->user()->id === $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot follow yourself',
            ]);
        }

        $existingFollow = Follower::where('user_id', $user->id)
            ->where('follower_id', auth()->user()->id)
            ->first();

        if ($existingFollow) {
            Follower::where('user_id', $user->id)
                ->where('follower_id', auth()->user()->id)
                ->delete();
            $action = 'unfollow';
        } else {
            Follower::create([
                'user_id' => $user->id,
                'follower_id' => auth()->user()->id,
            ]);
            $action = 'follow';
        }

        $followersCount = $user->countFollowers();

        return response()->json([
            'action' => $action,
            'isFollowing' => $action === 'follow',
            'followersCount' => $followersCount,
            'userId' => $user->id,
            'username' => $user->username
        ]);
    }

    public function followers(User $user)
    {
        $followers = User::whereIn('id', $user->followers()->pluck('follower_id'))
            ->paginate(20);

        return view('users.followers', compact('user', 'followers'));
    }

    public function followings(User $user)
    {
        $followings = User::whereIn('id', $user->followings()->pluck('user_id'))
            ->paginate(20);

        return view('users.followings', compact('user', 'followings'));
    }
}
