<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ModelBase extends Model
{
    public function getDateFormatedAttribute($value = null, $complete = false)
    {
        if($value === null)
            $date = Carbon::parse($this->created_at);
        else
            $date = Carbon::parse($this->{$value});

        if($complete === false)
            return $date->format('d/m/Y');
        else
            return $date->format('d/m/Y H:i');
    }

    public function date_formated()
    {
        return $this->getDateFormatedAttribute(...func_get_args());
    }

}
