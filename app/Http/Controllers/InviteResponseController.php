<?php

namespace App\Http\Controllers;

use App\Models\Invite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Devrabiul\ToastMagic\Facades\ToastMagic;


class InviteResponseController extends Controller
{
    public function accept($inviteId)
    {
        $invite = Invite::findOrFail($inviteId);

        if ($invite->user_id !== Auth::id()) {
            ToastMagic::error('شما اجازه پاسخ به این دعوت را ندارید.');
            return redirect()->back();
        }

        $invite->status = 'accepted';
        $invite->accepted_at = now();
        $invite->save();

        $notification = Auth::user()->notifications()
            ->where('data->invite_id', $inviteId)
            ->first();

        if ($notification) {
            $notification->markAsRead();
        }

        ToastMagic::success('شما با موفقیت دعوت به نظرسنجی را پذیرفتید.');
        return redirect()->route('election.show', $invite->election_id);
    }

    public function reject($inviteId)
    {
        $invite = Invite::findOrFail($inviteId);

        if ($invite->user_id !== Auth::id()) {
            ToastMagic::error('شما اجازه پاسخ به این دعوت را ندارید.');
            return redirect()->back();
        }
        $invite->status = 'declined';
        $invite->save();

        $notification = Auth::user()->notifications()
            ->where('data->invite_id', $inviteId)
            ->first();

        if ($notification) {
            $notification->markAsRead();
        }

        ToastMagic::success('دعوت به نظرسنجی رد شد.');
        return redirect()->back();
    }
}
