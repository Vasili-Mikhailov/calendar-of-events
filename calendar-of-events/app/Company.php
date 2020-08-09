<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    /**
     * connected table
     *
     *@var string
     */
    protected $table = 'companies';

    /**
     * get employees
     */
    public function users()
    {
        return $this->hasMany('App\User');
    }

    /**
     * get shifts
     */
    public function shifts()
    {
        return $this->hasMany('App\Shift');
    }
}
