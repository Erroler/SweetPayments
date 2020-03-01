<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'last_updated' => 'date'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        
    ];

    public function community() {
        return $this->hasOne('App\Models\Community');
    }

    public function account_history() {
        return $this->hasMany('App\Models\AccountLog');
    }

    public function tickets() {
        return $this->hasMany('App\Models\Ticket');
    }

    public function cannotWithdrawReason() {
        $last_account_log = $this->getLastAccountLog();
        if($last_account_log && $last_account_log->action === 'WITHDRAWL_REQUEST') {
            return 'pending_request';
        }
        else {
            if($last_account_log && $last_account_log->created_at->diffInDays() < 10)
                return 'request_too_soon';
            else {
                if($this->balance < 10)
                    return 'insufficient_balance';
            }
        }
        return null;
    }

    public function getLastAccountLog() {
        return $this->account_history()->latest('id')->first();
    }

    public function getLast30DaysInfo() {
        $info_this_month = \DB::query()->fromSub(function ($query) {
            $query->from('sales')
                ->select(\DB::raw('revenue_after_tax as revenue'))
                ->where('created_at', '>=', Carbon::now()->startOfMonth())
                ->where('completed', true)
                ->whereIn('subscription_id', 
                    $this->community->subscriptions()->pluck('id')
                );
        }, 'result')->select(\DB::raw('count(*) as number_sales'), \DB::raw('sum(revenue) as revenue'))->first();
        return $info_this_month;
    }

    public function getProfileLink() {
        return 'http://steamcommunity.com/profiles/'. $this->steamid;
    }

    public function isAdmin() {
        return $this->steamid === '76561198044937482';
    }
    
}
