<?php

namespace App\Http\Controllers\Taralite\V1; // changed from App\Http\Controllers


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Rate;
use Carbon\Carbon;

class RateController extends Controller
{
  public function index($pawal,$pakhir,$tsi)
   {
     $periodeAwal = new Carbon($pawal);
     $periodeAkhir= new Carbon($pakhir);
     if($periodeAwal >= $periodeAkhir)
      return response()->json(['message' => 'Periode Awal >= Periode Akhir', 'code' => 422], 422);
     $diff = $periodeAwal->diff($periodeAkhir);
     $diff = round(($diff->format('%y')*12) + $diff->format('%m') + ($diff->format('%d')/30));
     if($diff > 12){
       return response()->json(['data' => 'Check Periode, lebih dari 1 tahun',422],422);
     }
     $rate = DB::table('rates')
             ->where('idRate',$diff)
             ->pluck('rate');
      $premi = $rate[0] * $tsi;
       return response()->json([
                    'data' => [
                                'premi'   =>  $premi,
                                'periode' =>  $diff,
                                'rate'    =>  $rate[0],
                                'satuan'  =>  'permil'
                              ],
                    'code' => 200],200);
   }
}
