<?php

namespace App\Models;

use Carbon\Carbon;
use App\Traits\Hashidable;
use Illuminate\Database\Eloquent\Model;

class Community extends ModelBase
{

    use Hashidable;
    
    protected $guarded = [
        
    ];
    
    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    public function servers()
    {
        return $this->hasMany('App\Models\Server');
    }

    public function subscriptions()
    {
        return $this->hasMany('App\Models\Subscription');
    }

    public function getShopAddressAttribute() 
    {
        return $this->small_name;
    }

}
