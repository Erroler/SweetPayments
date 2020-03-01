<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Sale;
use App\Models\User;
use Carbon\CarbonPeriod;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class LandingPageController extends Controller
{
    public function index() 
    {
        return \File::get(public_path() . '/landing_page/index.html');
    }

    public function clientarea() 
    {
        return Redirect::route('auth.login', [], 301);
    }

    public function missing($file) 
    {
        return Redirect::to('/landing_page/'.$file, 302);
    }
}
