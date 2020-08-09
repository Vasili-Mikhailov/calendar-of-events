<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'name', 'price', 'type', 'company_id', 'user_id', 'date',
  ];
}
