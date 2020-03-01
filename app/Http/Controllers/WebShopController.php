<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\Sale;
use App\Models\Community;
use App\Models\Subscription;
use Aws\Lambda\LambdaClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Invisnik\LaravelSteamAuth\SteamAuth;
use GuzzleHttp\Exception\ServerException;

class WebShopController extends Controller
{
    public function index($webshop_name)
    {
        $community = Community::where('small_name', $webshop_name)->firstOrFail();
        $subscriptions = $community->subscriptions()->orderBy('name')->get();

        return view('pages.shop.index', compact('community', 'subscriptions'));
    }

    public function buySubscription($webshop_name, Subscription $subscription)
    {
        $community = $subscription->community;
        if ($community->small_name !== $webshop_name)
            abort(404);

        $steam = session('steam');

        return view('pages.shop.buy', compact('community', 'subscription', 'steam'));
    }

    public function checkout($webshop_name, Subscription $subscription)
    {
        $community = $subscription->community;
        if ($community->small_name !== $webshop_name)
            abort(404);

        $steam = session('steam');
        if (!$steam)
            abort(400);

        return view('pages.shop.checkout', compact('community', 'subscription', 'steam'));
    }

    public function buyPaypal($webshop_name, Subscription $subscription)
    {
        $community = $subscription->community;
        if ($community->small_name !== $webshop_name)
            abort(404);

        $steam = session('steam');
        if (!$steam)
            abort(400);

        if (!in_array('paypal', $subscription->payment_methods))
            abort(400);
            
        $after_tax = round($subscription->pricing - ($subscription->pricing * 0.10 + 0.35), 2);
        $sale = Sale::create([
            'subscription_id' => $subscription->id,
            'expires_on' => Carbon::now()->addDays($subscription->duration),
            'completed' => false,
            'player_name' => $steam->personaname,
            'steamid64' => $steam->steamID64,
            'ip_address' => $_SERVER['HTTP_CF_CONNECTING_IP'] ?? '127.0.0.1',
            'payment_method' => 'paypal',
            'revenue_before_tax' => $subscription->pricing,
            'revenue_after_tax' => $after_tax,
        ]);

        $sale_id = $sale->getRouteKey();

        return view('pages.shop.buy_paypal', compact('community', 'subscription', 'steam', 'sale_id'));
    }

    public function buyPaysafecard($webshop_name, Subscription $subscription)
    {
        $community = $subscription->community;
        if ($community->small_name !== $webshop_name)
            abort(404);

        $steam = session('steam');
        if (!$steam)
            abort(400);

        if (!in_array('paysafecard', $subscription->payment_methods))
            abort(400);
            
        return view('pages.shop.buy_paysafecard', compact('community', 'subscription', 'steam'));
    }

    public function buyPaysafecardSubmit($webshop_name, Subscription $subscription, Request $request)
    {
        $community = $subscription->community;
        if ($community->small_name !== $webshop_name)
            abort(404);

        $steam = session('steam');
        if (!$steam)
            abort(400);

        if (!in_array('paysafecard', $subscription->payment_methods))
            abort(400);

        $request->validate([
            'pin1' => 'required|size:4|regex:/[0-9]{4}/',
            'pin2' => 'required|size:4|regex:/[0-9]{4}/',
            'pin3' => 'required|size:4|regex:/[0-9]{4}/',
            'pin4' => 'required|size:4|regex:/[0-9]{4}/',
        ]);

        $pin = $request->get('pin1') . $request->get('pin2') . $request->get('pin3') . $request->get('pin4');
        Log::error('[paysafecard] User with steamid64: '.$steam->steamID64. '['.$steam->personaname.']. is trying to buy subscription'. $subscription->id. ' of community '.$community->small_name. '.');

        /**
         * DISCLAIMER: THIS PART OF THE CODE IS NOT COMPLETE.
         *   I NEVER RECEIVED PERMISSION FROM PAYSAFECARD TO INTEGRATE IT IN THIS PROJECT.
         */

        return response()->json([
            'result' => 'done'
        ]);
    }

    public function success($webshop_name) 
    {
        $community = Community::where('small_name', $webshop_name)->firstOrFail();

        $steam = session('steam');
        if (!$steam)
            abort(400);

        return view('pages.shop.success', compact('community'));
    }

    public function login($webshop_name, Subscription $subscription)
    {
        $this->steam->setRedirectUrl(route('webshop.auth.handle', ['webshop_name' => $webshop_name, 'subscription' => $subscription->getRouteKey()]));
        return $this->steam->redirect();
    }

    public function loginHandle($webshop_name, Subscription $subscription)
    {
        if ($this->steam->validate()) {
            $info = $this->steam->getUserInfo();

            if (!is_null($info)) {
                session(['steam' => $info]);

                return redirect(route('webshop.buy', ['webshop_name' => $webshop_name, 'subscription' => $subscription->getRouteKey()]));
            }
        }
        return $this->login($webshop_name, $subscription);
    }


    public function __construct(SteamAuth $steam)
    {
        $this->steam = $steam;
    }
}
