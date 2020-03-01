<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountLog extends ModelBase
{
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
        'extra_info' => 'array'
    ];

    public function user() {
        return $this->belongsTo('App\Models\User');
    }
}
