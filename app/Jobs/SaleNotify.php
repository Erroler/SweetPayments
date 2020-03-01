<?php

namespace App\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SaleNotify implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 5;

    /**
     * The number of seconds to wait before retrying the job.
     *
     * @var int
     */
    public $retryAfter = 1800;

    protected $callback_url;
    protected $sale;

    /**
     * Create a new job instance.
     *
     * @return void
     */ 
    public function __construct($callback_url, $sale)
    {
        $this->callback_url = $callback_url;
        $this->sale = $sale;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // \Log::error('Wow!!!'.$this->callback_url);
        // throw new \Exception('Job will fail.');
        $client = new \GuzzleHttp\Client;

        // \Log::error(print_r([
        //     'Secret_Key' => md5($this->sale->subscription->community->getRouteKey()),
        //     'Player_SteamID64' => $this->sale->steamid64,
        //     'Player_Name' => $this->sale->player_name,
        //     'Player_IP' => $this->sale->ip_address,
        //     'Subscription_Name' => $this->sale->subscription->name,
        //     'Expiration_Date' => Carbon::parse($this->sale->expires_on)->format('Y-m-d H:m'),
        //     'Payment_Method' => $this->sale->payment_method,
        //     'Revenue' => number_format($this->sale->revenue_after_tax, 2),
        // ], TRUE));

        $res = $client->post($this->callback_url, [
            'form_params' => [
                'Secret_Key' => md5($this->sale->subscription->community->getRouteKey()),
                'Player_SteamID64' => $this->sale->steamid64,
                'Player_Name' => $this->sale->player_name,
                'Player_IP' => $this->sale->ip_address,
                'Subscription_Name' => $this->sale->subscription->name,
                'Expiration_Date' => Carbon::parse($this->sale->expires_on)->format('Y-m-d H:m'),
                'Payment_Method' => $this->sale->payment_method,
                'Revenue' => number_format($this->sale->revenue_after_tax, 2),
            ]
        ]);
    }
}
