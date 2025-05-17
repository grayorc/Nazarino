<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Option;
use App\Models\Election;
use Illuminate\Http\Request;
use App\Models\Vote;
use App\Models\Comment;
use Illuminate\Support\Facades\Cache;
use App\Facades\AI;

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
    public function show(Request $request, Election $election, Option $option)
    {
        // TODO : put this in a middleware
        if($option->election_id != $election->id){
            abort(404);
        }

        $comments = $option->comments()->get()->sortByDesc('created_at');
        $option->user_vote = auth()->check() ? auth()->user()->userVote($option->id) : null;

        $option->comment_count = $option->comments()->count();
        return view('elections.options.single', compact('option', 'election','comments'));
    }

    protected function generateCommentSummary($option)
    {
        try {
            $comments = $option->comments;
            if ($comments->isEmpty()) {
                return null;
            }

            return AI::summarizeComments($option->toArray() + $comments->toArray());
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error generating comment summary: ' . $e->getMessage());
            return null;
        }
    }

    public function getAiSummary(Option $option)
    {
        $comments = $option->comments;

        if ($comments->count() < 3) {
            return response('<div class="text-gray-500 text-center">تعداد نظرات برای تحلیل کافی نیست (حداقل ۳ نظر نیاز است).</div>');
        }


        $cacheKey = 'comment_summary_' . $option->id;

        $summary = Cache::remember($cacheKey, now()->addDay(), function () use ($option) {
            return $this->generateCommentSummary($option);
        });

        if (!$summary) {
            return response('<div class="text-red-500 text-center">متأسفانه در تحلیل نظرات خطایی رخ داد. لطفاً دوباره تلاش کنید.</div>');
        }

        return response('<div class="rtl text-gray-800 dark:text-black">' . nl2br(e($summary)) . '</div>');
    }
}
