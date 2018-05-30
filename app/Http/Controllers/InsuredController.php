<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Insured;
use Validator;

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
        $input['key'] = $request['namaPeserta'].'|'.$request['noKontrak'].'|'.$request['periodeAkhir'];

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
