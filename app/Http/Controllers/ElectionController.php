<?php

namespace App\Http\Controllers;

use App\Models\Election;
use App\Models\Image;
use App\Notifications\InviteNotification;
use Illuminate\Http\Request;
use App\Models\Vote;
use App\Models\Option;

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

        $election = Election::create($data);
        if($request->hasFile('image')){
            //verify if it is image
            if($request->file('image')->getClientOriginalExtension() == 'png' || $request->file('image')->getClientOriginalExtension() == 'jpg' || $request->file('image')->getClientOriginalExtension() == 'jpeg'){
                $image = $request->file('image')->store('elections', 'public');
                $image = Image::create([
                    'path' => $image,
                    'imageable_id' => $election->id,
                    'imageable_type' => 'App\Models\Election',
                ]);
            }else{
                return redirect()->back()->with('error', 'فرمت تصویر معتبر نیست');
            }
        }
        return redirect()->route('options.create', $election->id);
//        $user->notify(new InviteNotification()([
//            'title' => 'به نظرسنجی جدید دعوت شدید!',
//            'message' => 'شما به یک نظرسنجی جدید دعوت شدید. بررسی کنید.',
//            'url' => route('elections.show', $election->id),
//        ]));
    }

    public function show(int $election)
    {
        $election = Election::find($election);
        $election->comments_count = $election->comments->count();
        $election->votes_count = $election->votes->count();
        $election->users_count = $election->options->map(function($option){
            $votes = Vote::where('option_id', $option->id)->get();
            $users = $votes->map(function($vote){
                return $vote->user;
            });
            return $users->count();
        })->unique()->sum();
        if($election == null){
            abort(404);
        }
        $options = $election->options;
        $options->load('votes','comments');
        return view('elections.single',compact('election','options'));
    }

    public function vote(Request $request)
    {
        if (!auth()->check()) {
            return response()->json([
                'message' => 'User not logged in',
            ]);
        }

        $data = $request->validate([
            'option_id' => 'required',
            'vote_type' => ['required', 'in:UP,DOWN']
        ]);
        // return $data;
        $vote_type = "";
        $option = Option::find($data['option_id']);
        if (Vote::where('user_id', auth()->user()->id)->where('option_id', $data['option_id'])->exists()) {
            $vote = Vote::where('user_id', auth()->user()->id)->where('option_id', $data['option_id'])->first();
            if ($data['vote_type'] == 'UP') {
                if ($vote->vote == 1) {
                    $vote->delete();
                    $vote_type = "NONE";
                } else {
                    $vote->update([
                        'vote' => 1
                    ]);
                    $vote_type = "UP";
                }
            }
            if ($data['vote_type'] == 'DOWN') {
                if ($vote->vote == -1) {
                    $vote->delete();
                    $vote_type = "NONE";
                } else {
                    $vote->update([
                        'vote' => -1
                    ]);
                    $vote_type = "DOWN";
                }
            }
            } else {
                if ($data['vote_type'] == 'UP') {
                    $q_vote_type = 1;
                    $vote_type = "UP";
                } else {
                    $q_vote_type = -1;
                    $vote_type = "DOWN";
                }
                $vote = Vote::create([
                    'user_id' => auth()->user()->id,
                    'option_id' => $data['option_id'],
                    'vote' => $q_vote_type,
                    'election_id' => $option->election_id
                ]);
            }
            return response()->json([
                'vote' => $vote->option->votes->sum('vote'),
                'vote_type' => $vote_type,
                'option_id' => $data['option_id'],
            ]);
    }
}
