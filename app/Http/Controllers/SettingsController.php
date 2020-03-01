<?php

namespace App\Http\Controllers;

use App\Models\Community;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    public function index()
    {
        $community = Auth::user()->community;
        return view('pages.dashboard.settings', compact('community'));
    }

    public function update(Request $request) 
    {
        $community = Auth::user()->community;

        $validated = $request->validate([
            'webshop_name' => ['min:3|string|min:3|max:20|regex:'.config('settings.STEAM_GROUP_REGEX')],
        ]);

        if(Community::where('small_name', $validated['webshop_name'])->first() != NULL){
            return redirect()->back()->withErrors(['community_url' => 'That steam group has already been registered.']);
        }
        
        $community->update([
            'small_name' => $validated['webshop_name']
        ]);

        return redirect()->back()->withStatus('WebShop URL edited successfuly.');
    }

    public function updateCallbackUrl(Request $request)
    {
        $validated = $request->validate([
            'callback_url' => 'nullable|url',
        ]);
        
        $community = Auth::user()->community;
        $community->update([
            'callback_url' => $validated['callback_url']
        ]);

        return redirect()->back()->withStatus('Callback URL edited successfuly.');
    }
}
