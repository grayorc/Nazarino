<?php

namespace App\Http\Controllers;

use App\Models\Election;
use App\Models\Image;
use App\Models\Role;
use App\Notifications\InviteNotification;
use App\Rules\AfterNow;
use Hekmatinasser\Verta\Verta;
use IcehouseVentures\LaravelChartjs\Facades\Chartjs;
use Illuminate\Http\Request;
use App\Models\Vote;
use App\Models\Option;

class ElectionController extends Controller
{
    public function index(Request $request)
    {
        $query = auth()->user()->elections();

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
            'user_id' => auth()->id(),
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
        if($election == null){
            abort(404);
        }
        $options = $election->options;
        if(auth()->check()){
            foreach ($options as $option) {
                $option->user_vote = auth()->check() ? auth()->user()->userVote($option->id) : null;
            }
        }
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

    private function convertDate($persianDateString)
    {
        $gregorianDate = Verta::parseFormat('Y/n/j', $persianDateString)->datetime();

        return $gregorianDate->format('Y-m-d H:i:s');
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

        return view('dash.elections.edit', compact('election'));
    }

    public function update(Request $request, Election $election)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'public' => ['nullable', 'string'],
            'comment' => ['nullable', 'string'],
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

        // Prepare data for update
        $data = [
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'end_date' => $endDate,
            'has_comment' => $request->has('comment'),
            'is_public' => $request->has('public'),
        ];

        // Update the election record
        $election->update($data);

        // Handle image upload if present
        if ($request->hasFile('image')) {
            try {
                if (!$request->file('image')->isValid()) {
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['image' => 'خطا در آپلود تصویر. لطفا دوباره تلاش کنید.']);
                }

                // Delete old image if exists
                if ($election->image) {
                    // Delete file from storage
                    Storage::disk('public')->delete($election->image->path);

                    // Delete image record
                    $election->image->delete();
                }

                // Store new image
                $imagePath = $request->file('image')->store('elections', 'public');

                // Create new image record
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
}
