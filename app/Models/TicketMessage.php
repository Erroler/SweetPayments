<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketMessage extends ModelBase
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

    public function ticket() {
        return $this->belongsTo('App\Models\Ticket');
    }

}
