<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInsuredsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('insureds', function (Blueprint $table) {
            $table->increments('idPesertaTaralite');
            $table->string('noKontrak');
            $table->double('besaranPinjaman');
            $table->date('periodeAwal');
            $table->date('periodeAkhir');
            $table->string('namaPeserta',200);
            $table->date('tglLahir');
            $table->string('noKTP',20);
            $table->text('alamat');
            $table->char('noTel',20);
            $table->integer('rate_id')->unsigned();
            $table->foreign('rate_id')->references('idRate')->on('rates');
            $table->string('key')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('insureds');
    }
}
