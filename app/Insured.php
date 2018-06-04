<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Insured extends Model
{
  protected $fillable = [
      'eNoKontrak', 'besaranPinjaman', 'periodeAwal', 'periodeAkhir', 'namaPeserta',
      'tglLahir', 'noKTP', 'alamat', 'noTel','rate_id','key'
  ];

  protected $hidden = ['idPesertaTaralite','updated_at','created_at'];
}
