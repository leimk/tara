<?php

namespace App\Http\Controllers\Taralite\V1;  // changed from App\Http\Controllers

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Insured;
use Validator;
use Carbon\Carbon;

class InsuredController extends Controller
{
  // public function getIp(){
    // foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key){
    //     if (array_key_exists($key, $_SERVER) === true){
    //         foreach (explode(',', $_SERVER[$key]) as $ip){
    //             $ip = trim($ip); // just to be safe
    //             if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false){
    //                 return $ip;
    //             }
    //         }
    //     }
    // }
    // return $_SERVER['REMOTE_ADDR'];
// }
  public function index($cAwal,$cAkhir)
   {
        // $insured = Insured::all();
       // return response()->json(['data' => $insured, 'code' => 200],200);
       $cAwal = new Carbon($cAwal);
       $cAkhir= new Carbon($cAkhir);
       $cAwal = $cAwal->format('Y-m-d');
       $cAkhir = $cAkhir->format('Y-m-d');
       $ip = \Request::ip();
       // $ip = $this->getIp();
       try{

         $insureds = DB::table('insureds')
              ->whereBetween('created_at',array($cAwal.' 00:00:00',$cAkhir.' 23:59:59'))
              ->get();
         if (count($insureds) == 0)
            return response()->json(['message'=>'No data Found', 200],200);
       } catch (\Exception $e) {
          return response()->json($e,404);
       }

      return response()->json(['data' => $insureds, 'ip' => $ip, 'code' => 200], 200);
   }

   public function show($noKontrak)
    {
        $ip = \Request::ip();
        // $ip = $this->getIp();
        try{
          $insureds = DB::table('insureds')
               ->where('noKontrak',$noKontrak)
               ->get();
          if (count($insureds) == 0)
            return response()->json(['message'=>'No data Found', 404],404);
        } catch (\Exception $e) {
            return response()->json($e,404);
        }
       return response()->json(['data' => $insureds, 'ip' => $ip, 'code' => 200], 200);
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
          return response()->json(['message' => 'Terdapat Pinjaman yang masih berjalan dengan No Kontrak : '.$noK, 'noKontrak' => $noKontrak, 422],422);
        }

        $periodeAwal = new Carbon($input['periodeAwal']);
        $periodeAkhir= new Carbon($input['periodeAkhir']);
        $diff = $periodeAwal->diff($periodeAkhir);
        $diff = round(($diff->format('%y')*12) + $diff->format('%m') + ($diff->format('%d')/30));
        $input['rate_id'] = $diff;

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
