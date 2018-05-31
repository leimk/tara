<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rate;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RateController extends Controller
{
  public function index($pawal,$pakhir)
   {
     $periodeAwal = new Carbon($pawal);
     $periodeAkhir= new Carbon($pakhir);
     $diff = $periodeAwal->diff($periodeAkhir);
     $diff = round(($diff->format('%y')*12) + $diff->format('%m') + ($diff->format('%d')/30));
     if($diff > 12){
       return response()->json(['data' => 'Check Periode, lebih dari 1 tahun',422],422);
     }
     $rate = DB::table('rates')
             ->where('idRate',$diff)
             ->pluck('rate');
       return response()->json(['data' => $rate , 'code' => 200],200);
   }
}
