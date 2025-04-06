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
        return view('dash.elections.all', compact('elections'));
    }

    public function create()
    {
        return view('dash.elections.create');
    }

    public function store(Request $request)
    {
        // dd($request);
        $data = $request->validate([
            'title' => 'required',
            'description' => 'required',
        ]);
        $data['user_id'] = auth()->user()->id;
        if($request->has('date-check') && $request->has('end_date')){
            if($request->end_date > now()){
                $data['end_date'] = $request->end_date;
            }else{
                return redirect()->back()->with('error', 'تاریخ انتخابی باید بیشتر از تاریخ حال باشد');
            }
        }
        $data['is_public'] = true;
        if($request->has('comment')){
            $data['has_comment'] = true;
        }else{
            $data['has_comment'] = false;
        }
        if($request->has('revote')){
            $data['is_revocable'] = true;
        }else{
            $data['is_revocable'] = false;
        }

        if($request->has('multivote')){
            $data['is_multivote'] = true;
        }else{
            $data['is_multivote'] = false;
        }


        Election::create($data);


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
