<?php
date_default_timezone_set('Asia/Jakarta');
use Illuminate\Database\Seeder;

class RatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      for($i=1;$i<=12;$i++)
      {
        DB::table('rates')->insert([
          'bulan'       =>  $i,
          'rate'        =>  round(mt_rand() / mt_getrandmax(),4),
          'created_at'  =>  now(),
          'updated_at'  =>  now()
        ]);
      }
    }
}
