<?php

namespace App\Http\Controllers;

use Chumper\Zipper\Zipper;
use Illuminate\Http\Request;

class ServersController extends Controller
{
    public function main()
    {
        $servers = \Auth::user()->community->servers()->orderBy('name')->get();
        
        return view('pages.dashboard.servers', compact('servers'));
    }

    public function plugin()
    {
        $zipper = new Zipper;

        $file_name = \Auth::user()->community->getRouteKey().'_plugin.zip';
        $zipper->make(storage_path($file_name))->folder('addons')->add(storage_path('plugin/addons/'));

        $cfg = file_get_contents(storage_path('plugin/cfg/sourcemod/sweetpayments.cfg'));
        $cfg = str_replace('KEY_EDIT', \Auth::user()->community->getRouteKey(), $cfg);

        $zipper->folder('cfg/sourcemod')->addString('sweetpayments.cfg', $cfg);
        $zipper->close();

        return response()->download(storage_path($file_name), 'sweetpaymantes_plugin.zip')->deleteFileAfterSend(true);;
    }
}
