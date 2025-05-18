<?php

namespace App\Http\Controllers;

use App\Models\Election;
use App\Models\Invite;
use App\Models\User;
use App\Notifications\InviteNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class InviteController extends Controller
{
    public function index(Election $election)
    {
        // TODO : move to a middleware
        if (Auth::id() !== $election->user_id) {
            abort(403);
        }

        return view('dash.elections.invite', compact('election'));
    }

    public function sendInvite(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => ['required','exists:users,id'],
            'election_id' => ['required','exists:elections,id'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid input data',
                'errors' => $validator->errors()
            ], 422);
        }

        $currentUser = auth()->user();
        $election = Election::find($request->election_id);
        $invitedUser = User::find($request->user_id);

        if ($currentUser->id !== $election->user_id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action.'
            ], 403);
        }

        if (!$invitedUser->isInvitedToElection($election->id)) {
            Invite::create([
                'election_id' => $election->id,
                'user_id' => $invitedUser->id,
                'status' => 'pending'
            ]);

            $notification = new InviteNotification([
                'title' => 'دعوت به نظرسنجی',
                'message' => 'شما به نظرسنجی "' . $election->title . '" دعوت شده‌اید.',
                'election_id' => $election->id,
                'url' => route('election.show', $election->id)
            ]);

            $invitedUser->notify($notification);

            return response()->json([
                'success' => true,
                'message' => 'دعوتنامه با موفقیت ارسال شد'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'error' => 'duplicated',
                'message' => 'این کاربر قبلاً به این نظرسنجی دعوت شده است.'
            ]);
        }
    }
}
