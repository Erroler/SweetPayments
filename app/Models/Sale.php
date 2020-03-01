<?php

namespace App\Models;

use App\Jobs\SaleNotify;
use App\Traits\Hashidable;
use danielme85\Geoip2\Facade\Reader;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Sale extends ModelBase
{

    use Hashidable;
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
        
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'expires_on'];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('completed', function (Builder $builder) {
            $builder->where('completed', true);
        });
    }


    public function getDate() 
    {
        return $this->created_at;
    }

    public function subscription() {
        return $this->belongsTo('App\Models\Subscription');
    }

    public function belongsToUser(User $user) {
        return $this->subscription->belongsToUser($user);
    }

    public function getLocation() {
        $value = Cache::remember('geoip2'.$this->ip_address, 60*60, function () {
            $reader = Reader::connect();
            try {
                $record = $reader->city($this->ip_address);
                return [
                    'city' => $record->city->name,
                    'country' => $record->country->name,
                ];
            }
            catch(\Exception $e) {
                return [
                    'city' => 'UNKNOWN',
                    'country' => 'UNKNOWN',
                ];
            }
        });
        return $value;
    }

    public function getLocationFormated() {
        $location = $this->getLocation();
        if($location['city'] !== null && $location['city'] !== 'UNKNOWN')
            return $location['city']. ', '. $location['country'];
        else 
            return $location['country'];
    }
    
    public function complete() {
        $this->update([
            'completed' => true
        ]);
        $callback_url = $this->subscription->community->callback_url;
        if($callback_url === null)
            return;

        SaleNotify::dispatch($callback_url, $this);
    }
}
