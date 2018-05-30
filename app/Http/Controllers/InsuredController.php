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
}
