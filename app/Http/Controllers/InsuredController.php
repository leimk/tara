<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Insured;
use Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InsuredController extends Controller
{
  public function index()
   {
       $insured = Insured::all();

       return response()->json(['data' => $insured, 'code' => 200],200);
   }

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
        if(count($cari)>0)
        {
          foreach($cari as $item){
            $noK .= $item->noKontrak.', ';
            $noKontrak[] = $item->noKontrak;
          }
          // $noK = explode('|',$cari[0]->key);
          // $pDB = new Carbon($noK[1]);
        //   if($pDB>now()){
            return response()->json(['data' => 'Terdapat Pinjaman yang masih berjalan dengan No Kontrak : '.$noK, 'noKontrak' => $noKontrak, 422],422);
          // }
        }
        $periodeAwal = new Carbon($input['periodeAwal']);
        $periodeAkhir= new Carbon($input['periodeAkhir']);
        $diff = $periodeAwal->diff($periodeAkhir);
        $diff = ($diff->format('%y')*12) + $diff->format('%m');
        $rate = DB::table('rates')
                ->where('idRate',$diff)
                ->pluck('rate');
        $input['rate'] = $rate;


        //TODO : VALIDATION FORMATS!

        // $input['diff'] = $diff;
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
            'key'             =>  'required'
        ]);

        if($validator->fails()){
            return response()->json(['data' => 'Validation Error', 'errMsg' => $validator->errors(), 422],422);
        }
        try{
            $insured = Insured::create($input);
        } catch (\Exception $e){

            // $errorCode = $e->errorInfo[2];
            //
            // if($errorCode == 1062){
                return response()->json(['data' => $e->errorInfo[2], 422],422);
            // }
        }

        return response()->json(['data' => $input, 200],200);
    }
}
