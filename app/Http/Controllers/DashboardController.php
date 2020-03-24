<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Sale;
use App\Models\User;
use Carbon\CarbonPeriod;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    // Dashboard main page.
    public function main() 
    {
        $steamid = Auth::user()->steamid;
        $community = Auth::user()->community;
        if($community === NULL)
            return redirect()->route('community.select.step1');

        $subscriptions = $community->subscriptions;

        $info_this_month = \DB::query()->fromSub(function ($query) {
            $query->from('sales')
                ->select(\DB::raw('revenue_after_tax as revenue'))
                ->where('created_at', '>=', Carbon::now()->startOfMonth())
                ->where('completed', true)
                ->whereIn('subscription_id', 
                    \Auth::user()->community->subscriptions()->pluck('id')
                );
        }, 'result')->select(\DB::raw('count(*) as number_sales'), \DB::raw('sum(revenue) as revenue'))->first();

        $revenue_month = $info_this_month->revenue;
        $number_sales_month = $info_this_month->number_sales;
        $servers = \Auth::user()->community->servers->count();
        $balance = \Auth::user()->balance;
        $month = Carbon::now()->isoFormat('MMM YYYY');

        return view('pages.dashboard.main', compact('community', 'subscriptions', 'revenue_month', 'number_sales_month', 'servers', 'balance', 'month'));
    }

    public function sales_chart_ajax(Request $request) 
    {
        // Extract number of days of sales to show.
        $timeframe = $request->query('timeframe', '30');
        if($timeframe > 365)
            abort(403);
        $display = $request->query('show_type', 'revenue');
        $subscription = $request->query('subscription', 'all');

        if($subscription !== 'all') {
            $subscription_id = \Hashids::connection(Subscription::class)->decode($subscription)[0] ?? null;
            $subscription = Subscription::findOrFail($subscription_id);
            if(!$subscription->belongsToUser(Auth::user()))
                abort(403);
        }

        if($timeframe <= 90) {
            $start_date_data = Carbon::today()->subDays($timeframe);
            $date_format = 'YYYY/MM/DD';
        } else {
            $start_date_data = Carbon::today()->subDays($timeframe)->startOfMonth();
            $date_format = 'YYYY/MM/01';
        }

        $result = Sale::select(\DB::raw('to_char("created_at", \''. $date_format . '\') as date'), \DB::raw('count(*) as sales'), \DB::raw('sum(revenue_after_tax) as revenue'))
                                ->where('created_at', '>=', $start_date_data)
                                ->whereIn('subscription_id', 
                                    $subscription === 'all' ? \Auth::user()->community->subscriptions()->pluck('id') : [$subscription->id]
                                )
                                ->groupBy('date')
                                ->orderBy('date')
                                ->get();

        $period = CarbonPeriod::create($start_date_data, 'today');

        foreach ($period as $day) {
            $contains = $result->contains(function ($value, $key) use ($day){
                return Carbon::create($value['date']) == $day;
            });
            if (!$contains) {
                if ($timeframe < 90 || $day->isoFormat('DD') === '01') {
                        $result->push([
                        'date' => $day->isoFormat('YYYY/MM/DD'),
                        'sales' => 0,
                        'revenue' => 0
                    ]);
                }
            }
        }
        
        $result = $result->sortBy('date');

        return \Response::json([
            'labels' => $result->pluck('date'),
            'datasets' => [[
                'label' => '# of Votes',
                'data' => $display === 'revenue' ? $result->pluck('revenue') : $result->pluck('sales'),
                'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                'borderColor' => 'rgba(54, 162, 235, 1)',
                'borderWidth' =>  1,
                'lineTension' => 0.05
            ]],
        ]);
    }
}
