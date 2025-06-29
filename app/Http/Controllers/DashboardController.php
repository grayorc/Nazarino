<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionUser;
use App\Models\Vote;
use App\Models\User;
use App\Models\Option;
use App\Models\Election;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use IcehouseVentures\LaravelChartjs\Facades\Chartjs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Number;


class DashboardController extends Controller
{
    /**
     * Display the dashboard with chart data.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        $remainingDays = $user->getRemainingDays();
        $totalVotes = $user->getTotalVotes();
        $totalElections = $user->totalActiveElections();
        $totalComments = $user->getTotalComments();

        if(\request()->has('verified')){
            ToastMagic::success('اکانت شما با موفقیت تایید شد');
        }

        $followers = $user->followers()
            ->with('follower')
            ->latest()
            ->take(4)
            ->get();

        $topElections = $user->elections()
            ->select('elections.id', 'elections.title', 'elections.is_open', DB::raw('COUNT(votes.id) as total_votes'))
            ->join('options', 'elections.id', '=', 'options.election_id')
            ->join('votes', 'options.id', '=', 'votes.option_id')
            ->groupBy('elections.id', 'elections.title')
            ->orderBy('total_votes', 'desc')
            ->limit(5)
            ->get();

        $electionTitles = $topElections->pluck('title')->toArray();
        $electionVotes = $topElections->pluck('total_votes')->toArray();

        $topElectionsChart = Chartjs::build()
            ->name('TopElectionsChart')
            ->type('bar')
            ->size(['width' => 700, 'height' => 300])
            ->labels($electionTitles)
            ->datasets([
                [
                    'label' => 'تعداد آرا',
                    'backgroundColor' => 'rgba(0, 123, 255, 0.7)', // Primary blue color
                    'borderWidth' => 0,
                    'borderRadius' => 4,
                    'maxBarThickness' => 30,
                    'data' => $electionVotes,
                ],
            ])
            ->options([
                'maintainAspectRatio' => false,
                'responsive' => true,
                'backgroundColor' => '#111827', // match dark:bg-gray-900
                'color' => '#fff',
                'layout' => [
                    'padding' => [
                        'left' => 15,
                        'right' => 15,
                        'top' => 20,
                        'bottom' => 10,
                    ],
                ],
                'plugins' => [
                    'title' => [
                        'display' => false,
                    ],
                    'legend' => [
                        'display' => false,
                    ],
                    'tooltip' => [
                        'enabled' => true,
                        'backgroundColor' => 'rgba(17, 24, 39, 0.9)',
                        'titleColor' => '#fff',
                        'bodyColor' => '#fff',
                        'padding' => 10,
                        'borderWidth' => 0,
                        'cornerRadius' => 4,
                        'displayColors' => false,
                    ],
                ],
                'scales' => [
                    'x' => [
                        'grid' => [
                            'display' => false,
                            'drawBorder' => false,
                        ],
                        'ticks' => [
                            'color' => 'rgba(255, 255, 255, 0.6)',
                            'font' => [
                                'size' => 12,
                            ],
                            'padding' => 5,
                        ],
                    ],
                    'y' => [
                        'beginAtZero' => true,
                        'grid' => [
                            'color' => 'rgba(255, 255, 255, 0.1)',
                            'drawBorder' => false,
                        ],
                        'border' => [
                            'display' => false,
                        ],
                        'ticks' => [
                            'color' => 'rgba(255, 255, 255, 0.6)',
                            'font' => [
                                'size' => 12,
                            ],
                            'padding' => 10,
                        ],
                    ],
                ],
            ]);

        return view('dash.index', compact(
            'topElectionsChart',
            'remainingDays',
            'topElections',
            'totalVotes',
            'totalElections',
            'totalComments',
            'followers'
        ));
    }
}
