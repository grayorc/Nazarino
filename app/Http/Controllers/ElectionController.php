<?php

namespace App\Http\Controllers;

use App\Models\Election;
use App\Notifications\InviteNotification;
use Illuminate\Http\Request;

class ElectionController extends Controller
{
    public function index()
    {
        $elections = Election::all();
    }

    public function create()
    {
        return view('elections.create');
    }

    public function store(Request $request)
    {
//        $user->notify(new InviteNotification()([
//            'title' => 'به نظرسنجی جدید دعوت شدید!',
//            'message' => 'شما به یک نظرسنجی جدید دعوت شدید. بررسی کنید.',
//            'url' => route('elections.show', $election->id),
//        ]));
    }

    public function show(Election $election)
    {
        
    }

}
