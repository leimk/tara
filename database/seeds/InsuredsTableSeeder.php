<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InsuredsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $faker = Faker\Factory::create('id_ID');
      $limit = 50;

      for ($i = 0; $i < $limit; $i++)
      {
        $name         = $faker->unique()->name;
        $noKTP        = $faker->unique()->nik;
        $periodeAkhir = $faker->unique()->dateTimeBetween($startDate = '+6 months', $endDate ='+1 years',$timezone = 'Asia/Jakarta');
        $periodeAkhir = $periodeAkhir->format('Y-m-d');
        $key = $noKTP.'|'.$periodeAkhir;
        DB::table('insureds')->insert([
          'noKontrak'         =>  $faker->unique()->creditCardNumber(),
          'besaranPinjaman'   =>  $faker->numberBetween($min = 1000000, $max = 200000000),
          'periodeAwal'       =>  '2018-05-30',
          'periodeAkhir'      =>  $periodeAkhir,
          'namaPeserta'       =>  $name,
          'tglLahir'          =>  $faker->dateTimeThisCentury($max = '-17 years', $timezone=null),
          'noKTP'             =>  $noKTP,
          'alamat'            =>  $faker->address(),
          'noTel'             =>  $faker->phoneNumber(),
          'rate_id'           =>  $faker->numberBetween(1,12),
          'key'               =>  $key,
          'created_at'        =>  now(),
          'updated_at'        =>  now()
        ]);
      }

    }
}
