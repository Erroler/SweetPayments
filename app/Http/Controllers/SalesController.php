<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Sale;
use App\Models\Community;
use Illuminate\Support\Str;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class SalesController extends Controller
{
    public function main()
    {
        return view('pages.dashboard.sales');
    }

    // public function test()
    // {
    //     return "";
    //     $subscription_ids = Auth::user()->community->subscriptions->pluck('id');
    //     $sales = Sale::with('subscription')->whereIn('subscription_id', $subscription_ids);

    //     //$sales = Auth::user()->with('community.subscriptions.sales');
    //     //$sales = $sales->community->subscriptions;
    //     dd($sales->get());

    //     return $sales->get();
    // }

    public function datatables(Subscription $subscription = null)
    {
        // Show sales for a particular subscription.
        if ($subscription != null) {
            if (!$subscription->belongsToUser(Auth::user()) && !Auth::user()->isAdmin())
                return abort(403);
            $subscription_ids = [$subscription->id];
        }
        // Show sales for all subscriptions
        else
            $subscription_ids = Auth::user()->community->subscriptions->pluck('id');

        $sales = Sale::with('subscription:id,name')
            ->whereIn('subscription_id', $subscription_ids)
            ->select('sales.id', 'created_at', 'expires_on', 'player_name', 'steamid64', 'ip_address', 'payment_method', 'revenue_before_tax', 'revenue_after_tax', 'subscription_id')
            ->latest('id');

        return DataTables::of($sales)
                        ->editColumn('date', function ($data) {
                            return $data->date_formated
                            ;
                        })->editColumn('expires_on', function ($data) {
                            return $data->date_formated('expires_on')
                            ;
                        })->editColumn('expires_on_non_format', function ($data) {
                            return $data->expires_on
                            ;
                        })->editColumn('id', function ($data) {
                            return $data->getRouteKey()
                            ;
                        })->editColumn('subscription.id', function ($data) {
                            return ''
                            ;
                        })->make(true);
    }

    public function show(Sale $sale)
    {
        $location = $sale->getLocation();

        $user = Auth::user();

        if (!$sale->belongsToUser($user) && !$user->isAdmin())
            return abort(403);

        $back_url = url()->previous();

        return view('pages.dashboard.view_sale', compact('sale', 'location'));
    }
}
