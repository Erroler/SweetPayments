<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Subscription;

class SubscriptionsController extends Controller
{
    public $flags = [
        'a' => 'Reserved slot access.',
        'b' => 'Generic admin; required for admins.',
        'c' => 'Kick other players.',
        'd' => 'Ban other players.',
        'e' => 'Remove bans.',
        'f' => 'Slay/harm other players.',
        'g' => 'Change the map or major gameplay features.',
        'h' => 'Change most cvars.',
        'i' => 'Execute config files.',
        'j' => 'Special chat privileges.',
        'k' => 'Start or create votes.',
        'l' => 'Set a password on the server.',
        'm' => 'Use RCON commands.',
        'n' => 'Change sv_cheats or use cheating commands.',
        'o' => 'Custom Group 1.',
        'p' => 'Custom Group 2.',
        'q' => 'Custom Group 3.',
        'r' => 'Custom Group 4.',
        's' => 'Custom Group 5.',
        't' => 'Custom Group 6.',
        'z' => 'Magically enables all flags and ignores immunity values.',
    ];

    public $payment_methods = ['paypal', 'paysafecard'];

    public $validation_rules = [
        'name' => 'required|string|min:3|max:20',
        'immunity' => 'required|numeric|integer|min:0|max:100',
        'duration' => 'required|numeric|integer|min:0|max:1095',
        'pricing' => 'required|numeric|min:1|max:50',
        'payment_methods' => 'required',
        'flags' => 'required'
    ];

    public function main() 
    {
        $community = Auth::user()->community;
        $flags = $this->flags;
        $subscriptions = Auth::user()->community->subscriptions()
                            ->orderBy('name')
                            ->select('*', \DB::raw('(SELECT count(*) FROM sales WHERE sales.subscription_id = subscriptions.id AND completed = true) as sales'))
                            ->get();
        return view('pages.dashboard.subscriptions', compact('community', 'flags', 'subscriptions'));
    }

    public function create(Request $request)
    {
        $validated = $request->validate($this->validation_rules);
        // Validate payment methods.
        foreach($validated['payment_methods'] as $payment_method) {
            if (!in_array($payment_method, $this->payment_methods)) {
                return redirect()->back()->withErrors(['payment_methods' => 'Invalid payment method selected.']);
            }
        }
        // Validate flags
        foreach($validated['flags'] as $flag) {
            if (!array_key_exists($flag, $this->flags)) {
                return redirect()->back()->withErrors(['flag' => 'Invalid flag selected.']);
            }
        }

        Subscription::create(array_merge($validated, [
            'community_id' => Auth::user()->community->id
        ]));

        return redirect()->back()->withStatus('Subscription created successfuly.');
    }

    public function edit(Request $request, Subscription $subscription) {
        $validated = $request->validate($this->validation_rules);
        //
        if(!$subscription->belongsToUser(Auth::user()))
            return redirect()->back();
        $subscription->update($validated);
        return redirect()->back()->withStatus('Subscription edited successfuly.');
    }

    public function showSales(Subscription $subscription) 
    {
        if(!$subscription->belongsToUser(Auth::user()) && !Auth::user()->isAdmin())
            return redirect()->back();

        return view('pages.dashboard.subscriptions_sales', compact('subscription'));
    }
}
