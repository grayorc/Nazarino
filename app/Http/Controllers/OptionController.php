<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Option;
use App\Models\Election;
use Illuminate\Http\Request;
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
    public function create(Request $request, $id)
    {
        // Get the election and its options
        $election = Election::findOrFail($id);
        $options = Option::where('election_id', $id)->get();

        return view('dash.Options.create', compact('election', 'options'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'image' => ['nullable','file','image','mimes:jpeg,png,jpg,gif,svg','max:5120'],
        ]);
        if(Election::find($request->election) === null){
            return redirect()->back()->with('error', 'نظرسنجی مورد نظر یافت نشد');
        }
        if (Election::find($request->election)->user_id !== auth()->user()->id) {
            return redirect()->back()->with('error', 'شما اجازه ایجاد گزینه برای این نظرسنجی را ندارید');
        }
        $data['election_id'] = $request->election;
        $option = Option::Create($data);
        if($request->hasFile('image')){
            $image = $request->file('image')->store('options', 'public');
            $image = Image::create([
                'path' => $image,
                'imageable_id' => $option->id,
                'imageable_type' => 'App\Models\Option',
            ]);
        }
        return redirect()->route('options.create', $request->election)->with('success', 'گزینه با موفقیت ایجاد شد');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Election $election, Option $option)
    {
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

    /**
     * Show the form for editing the specified option.
     */
    public function edit(Election $election, Option $option)
    {
        if (!$election) {
            $election = Election::findOrFail($option->election_id);
        }

        return view('dash.Options.edit', compact('option', 'election'));
    }

    /**
     * Update the specified option in storage.
     */
    public function update(Request $request, Election $election, Option $option)
    {
        $data = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'image' => ['nullable','file','image','mimes:jpeg,png,jpg,gif,svg','max:5120']
        ]);

        $option->update($data);

        if ($request->hasFile('image')) {
            if ($option->image) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($option->image->path);
                $option->image->delete();
            }

            $imagePath = $request->file('image')->store('options', 'public');
            Image::create([
                'path' => $imagePath,
                'imageable_id' => $option->id,
                'imageable_type' => 'App\Models\Option',
            ]);
        }

        return redirect()->route('options.create', $option->election_id)
            ->with('success', 'گزینه با موفقیت بروزرسانی شد');
    }
}
