<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Endors extends Model
{
  protected $fillable = [
      'noKontrak', 'id_Peserta','besaranPinjaman', 'periodeAwal', 'periodeAkhir', 'namaPeserta',
      'tglLahir', 'noKTP', 'alamat', 'noTel','rate_id','key','remarks'
  ];

  protected $hidden = ['idEndPeserta','updated_at','created_at'];
}
