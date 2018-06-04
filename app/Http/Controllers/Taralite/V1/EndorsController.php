<?php

namespace App\Http\Controllers\Taralite\V1;

// use App\Http\Controllers\Taralite\V1\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Endors;
use Validator;
use Carbon\Carbon;

class EndorsController extends Controller
{
  public function store(Request $request)
   {
       $input = $request->all();
       $input['key'] = $request['noKTP'].'|'.$request['periodeAkhir'];

       $cari = DB::table('insureds')
                    ->select('key','noKontrak')
                    ->where([
                         ['noKTP','=',$input['noKTP']],
                         ['periodeAkhir','>',now()]
                       ])
                    ->get();
       $noK ='';
       if(count($cari)==0)
       {
         foreach($cari as $item){
           $noK .= $item->noKontrak.', ';
           $noKontrak[] = $item->noKontrak;
         }
         return response()->json(['message' => 'Terdapat Pinjaman yang masih berjalan dengan No Kontrak : '.$noK, 'noKontrak' => $noKontrak, 422],422);
       }

       $periodeAwal = new Carbon($input['periodeAwal']);
       $periodeAkhir= new Carbon($input['periodeAkhir']);
       $diff = $periodeAwal->diff($periodeAkhir);
       $diff = round(($diff->format('%y')*12) + $diff->format('%m') + ($diff->format('%d')/30));
       $input['rate_id'] = $diff;

       //TODO : VALIDATION FORMATS!

       $validator = Validator::make($input, [
           'eNoKontrak'       =>  'required',
           'besaranPinjaman' =>  'required',
           'periodeAwal'     =>  'required',
           'periodeAkhir'    =>  'required',
           'namaPeserta'     =>  'required',
           'tglLahir'        =>  'required',
           'noKTP'           =>  'required',
           'alamat'          =>  'required',
           'noTel'           =>  'required',
           'rate_id'         =>  'required',
           'key'             =>  'required',
           'remarks'         =>  'required'
       ]);

       if($validator->fails())
           return response()->json(['message' => 'Validation Error '.$validator->errors(), 422],422);


       try{
           $insured = Insured::create($input);
       } catch (\Exception $e){
           // $errorCode = $e->errorInfo[2];
           //
           // if($errorCode == 1062){
               return response()->json(['message' => $e->errorInfo[2], 422],422);
           // }
       }

       return response()->json(['data' => $input, 200],200);
   }
}
