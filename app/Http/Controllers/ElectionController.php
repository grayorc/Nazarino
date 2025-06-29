<?php

namespace App\Http\Controllers;

use App\Models\Election;
use App\Models\Image;
use App\Models\Role;
use App\Models\AiAnalysis;
use App\Notifications\InviteNotification;
use App\Rules\AfterNow;
use Hekmatinasser\Verta\Verta;
use IcehouseVentures\LaravelChartjs\Facades\Chartjs;
use Illuminate\Http\Request;
use App\Models\Vote;
use App\Models\Option;
use App\Models\Comment;
use Illuminate\Support\Facades\Cache;
use App\Facades\AI;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ElectionsExport;
use App\Exports\OptionStatsExport;

class ElectionController extends Controller
{
    public function index(Request $request)
    {
        $query = Auth::user()->elections();

        if ($request->has('search') && !empty($request->input('search'))) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        if ($request->has('filter')) {
            switch ($request->input('filter')) {
                case 'visible':
                    $query->where('is_public', true);
                    break;
                case 'hidden':
                    $query->where('is_public', false);
                    break;
                case 'all':
                    break;
            }
        }

        if ($request->has('status')) {
            switch ($request->input('status')) {
                case 'open':
                    $query->where('is_open', true);
                    break;
                case 'closed':
                    $query->where('is_open', false);
                    break;
            }
        }

        $elections = $query->paginate(5);

        $elections->appends($request->all());

        return view('dash.elections.all', compact('elections'))
            ->fragmentIf(request()->hasHeader('HX-Request') && $request->has('search'), 'table-section');
    }

    public function create()
    {
        return view('dash.elections.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => ['required','string','max:255'],
            'description' => ['required','string'],
            'public' => ['nullable','string'],
            'comment' => ['nullable','string'],
            'has_end_date' => ['nullable','string'],
            'image' => ['nullable','file','image','mimes:jpeg,png,jpg,gif,svg','max:5120'],
        ]);

        if ($request->has('has_end_date') && $request->input('has_end_date') === "on") {
            $request->validate([
                'end_date' => ['required', new AfterNow()]
            ]);

            $endDate = $this->convertDate($request->input('end_date'));
        } else {
            $endDate = null;
        }

        $data = [
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'end_date' => $endDate,
            'user_id' => Auth::id(),
            'has_comment' => $request->has('comment'),
            'is_public' => $request->has('public'),
        ];
        $election = Election::create($data);

        if ($request->hasFile('image')) {
            try {
                if (!$request->file('image')->isValid()) {
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['image' => 'خطا در آپلود تصویر. لطفا دوباره تلاش کنید.']);
                }

                $imagePath = $request->file('image')->store('elections', 'public');

                Image::create([
                    'path' => $imagePath,
                    'imageable_id' => $election->id,
                    'imageable_type' => 'App\Models\Election',
                ]);
            } catch (\Exception $e) {
                $election->delete();

                return redirect()->back()
                    ->withInput()
                    ->withErrors(['image' => 'خطا در آپلود تصویر. لطفا دوباره تلاش کنید.']);
            }
        }

        return redirect()->route('options.create', $election->id);
    }

    public function show(Election $election)
    {
//        $election->withRelationshipAutoloading();
        $options = $election->options;
        if(auth()->check()){
            foreach ($options as $option) {
                $option->user_vote = auth()->user()->userVote($option->id);
            }
        }
        return view('elections.single',compact('election','options'));
    }

    public function vote(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'message' => 'User not logged in',
            ]);
        }

        $data = $request->validate([
            'option_id' => 'required',
            'vote_type' => ['required', 'in:UP,DOWN']
        ]);
        $vote_type = "";
        $option = Option::find($data['option_id']);
        if (Vote::where('user_id', Auth::user()->id)->where('option_id', $data['option_id'])->exists()) {
            $vote = Vote::where('user_id', Auth::user()->id)->where('option_id', $data['option_id'])->first();
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
                    'user_id' => Auth::user()->id,
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

    public function getAiAnalysis(Election $election)
    {
        $allComments = collect();

        foreach ($election->options as $option) {
            $comments = $option->comments;
            if ($comments && $comments->count() > 0) {
                $allComments = $allComments->merge($comments);
            }
        }

        if ($allComments->count() < 3) {
            return response('<div class="text-gray-300 text-center">تعداد نظرات برای تحلیل کافی نیست (حداقل ۳ نظر نیاز است).</div>');
        }


        $analysis = $election->aiAnalysis()->latest()->first();


        if(!$analysis || $analysis->created_at < now()->subDays(1)){
            $content = $this->sendElectionToAI($election);

            if (!$content) {
                return response('<div class="text-red-500 text-center">متأسفانه در تحلیل نظرات خطایی رخ داد. لطفاً دوباره تلاش کنید.</div>');
            }

            $analysis = new AiAnalysis([
                'user_id' => Auth::user()->id,
                'election_id' => $election->id,
                'content' => $content
            ]);
            $analysis->save();
        }

        return response('<div class="rtl text-gray-200">' . Str::markdown($analysis->content) . '</div>');
    }



    public function sendElectionToAI(Election $election)
    {
        try {

            if ($election->options->count() < 3) {
                return null;
            }

            $optionsWithComments = [];

            foreach ($election->options as $option) {
                $comments = [];

                foreach ($option->comments as $comment) {
                    $comments[] = [
                        'body' => $comment->body,
                    ];
                }

                $optionsWithComments[] = [
                    'title' => $option->title,
                    'description' => $option->description,
                    'votes_count' => $option->votes->count(),
                    'upvotes_count' => $option->votes->where('vote', 1)->count(),
                    'downvotes_count' => $option->votes->where('vote', -1)->count(),
                    'comments' => $comments
                ];
            }

            $electionData = [
                'title' => $election->title,
                'description' => $election->description,
                'options' => $optionsWithComments
            ];
            return AI::analyzeElectionWithOptions($electionData);

        } catch (\Exception $e) {
            Log::error('Error sending election to AI: ' . $e->getMessage());
            return null;
        }
    }

    private function convertDate($persianDateString)
    {
        $gregorianDate = Verta::parseFormat('Y/n/j', $persianDateString)->datetime();

        return $gregorianDate->format('Y-m-d H:i:s');
    }

    public function feed(Request $request)
    {
        $query = Election::where('is_public', true);

        if ($request->has('search') && !empty($request->input('search'))) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        if ($request->has('status')) {
            switch ($request->input('status')) {
                case 'open':
                    $query->where('is_open', true);
                    break;
                case 'closed':
                    $query->where('is_open', false);
                    break;
            }
        }

        $elections = $query->with(['user', 'options'])->latest()->paginate(6);

        if(auth()->check()) {
            foreach ($elections as $election) {
                foreach ($election->options as $option) {
                    $option->user_vote = auth()->user()->userVote($option->id);
                }
            }
        }

        $elections->appends($request->all());

        if ($request->header('HX-Request')) {
            return view('elections.feed', compact('elections'))->fragment('elections-grid');
        }

        return view('elections.feed', compact('elections'));
    }

    public function showResult(Election $election)
    {
        $options = Option::where('election_id', $election->id)
            ->withCount([
                'votes as upvotes_count' => function ($query) {
                    $query->where('vote', 1);
                },
                'votes as downvotes_count' => function ($query) {
                    $query->where('vote', -1);
                },
            ])
            ->get();

        $labels = $options->pluck('title')->toArray();
        $upvotes = $options->pluck('upvotes_count')->toArray();
        $downvotes = $options->pluck('downvotes_count')->toArray();

        $totalVotes = [];
        foreach ($options as $option) {
            $totalVotes[] = $option->upvotes_count + $option->downvotes_count;
        }

        // Bar Chart
        $barChart = Chartjs::build()
            ->name('VotingResultsBarChart')
            ->type('bar')
            ->size(['width' => 700, 'height' => 350])
            ->labels($labels)
            ->datasets([
                [
                    'label' => 'رای مثبت',
                    'backgroundColor' => 'rgba(38, 185, 154, 0.7)',
                    'data' => $upvotes,
                ],
                [
                    'label' => 'رای منفی',
                    'backgroundColor' => 'rgba(255, 99, 132, 0.7)',
                    'data' => $downvotes,
                ],
            ])
            ->options([
                'plugins' => [
                    'title' => [
                        'display' => true,
//                        'text' => 'نتایج رای گیری',
                    ],
                    'legend' => [
                        'position' => 'top',
                    ],
                ],
                'scales' => [
                    'y' => [
                        'beginAtZero' => true,
                    ],
                ],
            ]);

        // Pie Chart
        $pieChart = Chartjs::build()
            ->name('VotingResultsPieChart')
            ->type('pie')
            ->size(['width' => 400, 'height' => 400])
            ->labels($labels)
            ->datasets([
                [
                    'label' => 'Total Votes',
                    'backgroundColor' => [
                        'rgba(38, 185, 154, 0.7)',
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 206, 86, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                    ],
                    'data' => $totalVotes,
                    'borderWidth' => 0
                ]
            ])
            ->options([
                'plugins' => [
                    'title' => [
                        'display' => true,
//                        'text' => 'تعداد رای های هر گزینه',
                    ],
                    'legend' => [
                        'position' => 'right',
                    ],
                ],
            ]);

        return view('dash.elections.results', compact('barChart', 'pieChart', 'election'));
    }

    public function edit(Election $election)
    {
        $options = $election->options()->get();

        return view('dash.elections.edit', compact('election', 'options'));
    }

    public function update(Request $request, Election $election)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'public' => ['nullable', 'string'],
            'comment' => ['nullable', 'string'],
            'open' => ['nullable', 'string'],
            'has_end_date' => ['nullable', 'string'],
            'image' => ['nullable', 'file', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:5120'],
        ]);

        // Handle end date
        if ($request->has('has_end_date') && $request->input('has_end_date') === "on") {
            $request->validate([
                'end_date' => ['required', new AfterNow()]
            ]);

            $endDate = $this->convertDate($request->input('end_date'));
        } else {
            $endDate = null;
        }

        $data = [
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'end_date' => $endDate,
            'has_comment' => $request->has('comment'),
            'is_public' => $request->has('public'),
            'is_open' => $request->has('open'),
        ];

        $election->update($data);

        if ($request->hasFile('image')) {
            try {
                if (!$request->file('image')->isValid()) {
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['image' => 'خطا در آپلود تصویر. لطفا دوباره تلاش کنید.']);
                }

                // Delete old image if exists
                if ($election->image) {
                    Storage::disk('public')->delete($election->image->path);

                    $election->image->delete();
                }

                $imagePath = $request->file('image')->store('elections', 'public');

                Image::create([
                    'path' => $imagePath,
                    'imageable_id' => $election->id,
                    'imageable_type' => 'App\Models\Election',
                ]);
            } catch (\Exception $e) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['image' => 'خطا در آپلود تصویر. لطفا دوباره تلاش کنید.']);
            }
        }

        return redirect()->route('elections.index', $election->id)
            ->with('success', 'نظرسنجی با موفقیت بروزرسانی شد.');
    }

    public function destroy(Election $election)
    {
        $election->delete();
        return redirect()->route('elections.index')
            ->with('success', 'نظرسنجی با موفقیت حذف شد.');
    }

    /**
     * Export elections to Excel
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export(Request $request)
    {
        // Check if user has permission to export
        if (!Auth::user()->can('excel-export')) {
            return redirect()->route('elections.index')
                ->with('error', 'شما دسترسی لازم برای خروجی اکسل را ندارید.');
        }

        $query = Auth::user()->elections();

        // Apply the same filters as the index method
        if ($request->has('search') && !empty($request->input('search'))) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        if ($request->has('filter')) {
            switch ($request->input('filter')) {
                case 'visible':
                    $query->where('is_public', true);
                    break;
                case 'hidden':
                    $query->where('is_public', false);
                    break;
                case 'all':
                    break;
            }
        }

        if ($request->has('status')) {
            switch ($request->input('status')) {
                case 'open':
                    $query->where('is_open', true);
                    break;
                case 'closed':
                    $query->where('is_open', false);
                    break;
            }
        }

        $elections = $query->with(['user', 'options'])->get();

        return Excel::download(new ElectionsExport($elections), 'نظرسنجی-ها-' . verta()->format('Y-m-d') . '.xlsx');
    }

    public function exportSingle(Election $election)
    {
        //make file name
        $filename = 'آمار-گزینه-های-' . Str::slug($election->title) . '-' . verta()->format('Y-m-d') . '.xlsx';
        return Excel::download(new OptionStatsExport($election), $filename);
    }
}
