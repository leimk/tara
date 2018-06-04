<?php
namespace App\Helpers;
use Carbon\Carbon;

class Helper
{
    public static function diff($pAwal,$pAkhir)
    {
      $periodeAwal = new Carbon($pAwal);
      $periodeAkhir= new Carbon($pAkhir);

      return $periodeAwal->diff($periodeAkhir);
    }
}

 ?>
