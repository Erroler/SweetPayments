<?php

namespace App\Models;

use App\Models\User;
use App\Traits\Hashidable;
use Illuminate\Database\Eloquent\Model;

class Subscription extends ModelBase
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
        'payment_methods' => 'array',
        'flags' => 'array'
    ];

    public function sales()
    {
        return $this->hasMany('App\Models\Sale');
    }

    public function community() 
    {
        return $this->belongsTo('App\Models\Community');
    }

    public function belongsToUser(User $user) {
        if($this->community->user->id === $user->id)
            return true;
        else
            return false;
    }

    public function durationFormatted() {
        if($this->duration === 0) return 'permanent';
        else if($this->duration % 30 === 0)
        {
            if ($this->duration === 30)  return '1 month';
            else   return ($this->duration % 30). ' months';
        }
        else if($this->duration % 365 === 0) {
            if ($this->duration === 365)  return '1 year';
            else   return ($this->duration % 365). ' years';
        }
        else if($this->duration === 1) return '1 day';
        else return $this->duration. ' days';
    }

}
