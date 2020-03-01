<?php

namespace App\Http\Controllers;

use App\Models\Community;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function users()
    {
        $users = User::has('community')->with('community')->get()->sortBy('community.full_name');
        return view('pages.dashboard.admin.users', compact('users'));
    }

    public function servers(Community $community)
    {
        $servers = $community->servers()->orderBy('name')->get();
        $admin_view = true;
        return view('pages.dashboard.servers', compact('community', 'servers', 'admin_view'));
    }

    public function subscriptions(Community $community)
    {
        $flags = [];
        $subscriptions = $community->subscriptions()
                            ->orderBy('name')
                            ->select('*', \DB::raw('(SELECT count(*) FROM sales WHERE sales.subscription_id = subscriptions.id AND completed = true) as sales'))
                            ->get();      
        $admin_view = true;
        return view('pages.dashboard.subscriptions', compact('community', 'subscriptions', 'flags', 'admin_view'));
    }
}
