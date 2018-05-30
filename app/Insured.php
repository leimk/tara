<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Insured extends Model
{
  protected $fillable = [
      'noKontrak', 'besaranPinjaman', 'periodeAwal', 'periodeAkhir', 'namaPeserta',
      'tglLahir', 'noKTP', 'alamat', 'noTel','rate_id'
  ];

  protected $hidden = ['idPesertaTaralite','key','updated_at','created_at'];
}
