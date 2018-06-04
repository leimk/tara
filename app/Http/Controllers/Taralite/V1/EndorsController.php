<?php

namespace App\Http\Controllers\Taralite\V1;

// use App\Http\Controllers\Taralite\V1\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Endors;
use App\Insured;
use Validator;
use Carbon\Carbon;
use Helper;


class EndorsController extends Controller
{

  public function store(Request $request)
   {
       $input = $request->all();
       $cari = DB::table('insureds')
                     ->where('noKontrak',$input['noKontrak'])
                     ->get();
       if(count($cari)==0)
         return response()->json(['message' => 'Data Not Found', 404],404);

       $input['key'] = $request['noKTP'].'|'.$request['periodeAkhir'];
       $diff = Helper::diff($input['periodeAwal'],$input['periodeAkhir']);
       $diff = round(($diff->format('%y')*12) + $diff->format('%m') + ($diff->format('%d')/30));
       $input['rate_id'] = $diff;
       $input['id_Peserta'] = $cari[0]->idPesertaTaralite;
       //TODO : VALIDATION FORMATS!

       $validator = Validator::make($input, [
           'noKontrak'       =>  'required',
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
           return response()->json(['message' => $validator->errors(), 422],422);


       try{
           $insured = Endors::create($input);
       } catch (\Exception $e){
           // $errorCode = $e->errorInfo[2];
           //
           // if($errorCode == 1062){
               return response()->json(['message' => $e->errorInfo[2], 422],422);
           // }
       }

       return response()->json(['data' => $cari, 200],200);
   }
}
