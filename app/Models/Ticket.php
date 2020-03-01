<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends ModelBase
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
        'last_updated' => 'date'
    ];

    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    public function updated_ago() {
        return $this->last_updated->diffForHumans();
    }

    public function messages() {
        return $this->hasMany('App\Models\TicketMessage');
    }
}
