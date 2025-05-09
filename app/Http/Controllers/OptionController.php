<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Option;
use App\Models\Election;
use Illuminate\Http\Request;
use App\Models\Vote;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class OptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dash.Options.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
//        dd($request->all(),$request->id);
        $data = $request->validate([
            'title' => 'required',
            'description' => 'required',
        ]);
        $data['election_id'] = $request->id;
        // dd($data);
        $option = Option::Create($data);
        if($request->hasFile('image')){
            if($request->file('image')->getClientOriginalExtension() == 'png' || $request->file('image')->getClientOriginalExtension() == 'jpg' || $request->file('image')->getClientOriginalExtension() == 'jpeg'){
                $image = $request->file('image')->store('options', 'public');
                $image = Image::create([
                    'path' => $image,
                    'imageable_id' => $option->id,
                    'imageable_type' => 'App\Models\Option',
                ]);
            }else{
                return redirect()->back()->with('error', 'فرمت تصویر معتبر نیست');
            }
        }
        return redirect()->route('options.create', $request->id)->with('success', 'گزینه با موفقیت ایجاد شد');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $election_id, string $option_id)
    {
        $option = Option::find($option_id);
        $option->withRelationshipAutoloading();
        $election = Election::find($election_id);
//        $election->withRelationshipAutoloading();
        $comments = Comment::where('commentable_id', $option->id)->get()->sortByDesc('created_at');
        $comments->withRelationshipAutoloading();

        $option->user_vote = Auth::hasUser() ? Auth::user()->userVote($option->id) : 0;

        $option->comment_count = $option->comments()->count();
        return view('elections.options.single', compact('option', 'election','comments'));
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
    public function destroy(string $id)
    {
        //
    }
}
