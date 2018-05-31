<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
  protected $fillable = [
      'bulan','rate'
  ];

  protected $hidden = ['idRate'];
}
