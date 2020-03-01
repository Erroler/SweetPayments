<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Sale;
use App\Models\Community;
use App\Models\Server;
use Illuminate\Http\Request;

class PluginApiController extends Controller
{
    public function main(Request $request)
    {
        // $community = Community::first();
        // $steamid64 = '76561198851714704';
        // dd([
        //     $request->header('key'),
        //     $request->header('steamid64')
        // ]);

        // return response()->json([
        //     'flags' => $request->header('key'),
        //     'immunity' => $request->header('steamid64')
        // ]);

        $community_id = \Hashids::connection(Community::class)->decode($request->header('key'))[0] ?? null;
        $community = Community::find($community_id);

        if($community === null)
           abort(403);
 
        $action = $request->header('action');

        if ($action === 'player_info') {
            $steamid64 = $request->header('steamid64');

            if($steamid64 === null)
            abort(403);

            $sales = Sale::with('subscription')
                ->whereIn('subscription_id', $community->subscriptions->pluck('id'))
                ->where('expires_on', '>=', Carbon::now())
                ->where('steamid64', $steamid64)
                ->get();

            $flags = [];
            $immunity = 0;
            foreach($sales as $sale) {
                $subscription = $sale->subscription;
                foreach ($subscription->flags as $flag) {
                    $flags[$flag] = true;
                }
                if($subscription->immunity > $immunity) {
                    $immunity = $subscription->immunity;
                }
            }
            $flags = join('', array_keys($flags));

            return response()->json([
                'flags' => $flags,
                'immunity' => $immunity
            ]);
        }
        else if ($action === 'server_update') {
            $max_players = $request->header('maxplayers');
            $current_players = $request->header('currentplayers');
            $current_map = $request->header('currentmap');
            $server_address = $request->header('serveraddress');
            $server_name = $request->header('servername');

            $server = Server::where('address', $server_address)->first();
            if($server === null) {
                Server::create([
                    'name' => $server_name,
                    'players' => $current_players.'/'.$max_players,
                    'map' => $current_map,
                    'community_id' => $community_id,
                    'address' => $server_address
                ]);
            }
            else if($server->community_id == $community_id) {
                $server->update([
                    'name' => $server_name,
                    'players' => $current_players.'/'.$max_players,
                    'map' => $current_map
                ]);  
            }
            
            return response()->json([]);
        }
        else {
            abort(400);
        }
    }
}
