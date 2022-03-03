<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVaksinasisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vaksinasis', function (Blueprint $table) {
            $table->id();
            $table->integer('id_laporan')->nullable();
            $table->integer('laporanCovid19Versi3Id')->nullable();
            $table->integer('id_vaksinasi')->nullable();
            $table->integer('dosisVaksinId')->nullable();
            $table->integer('jenisVaksinId')->nullable();

            $table->date('OnsetDate')->nullable();
            $table->dateTime('tanggal_ambil_data')->comment('dari sphaira');
            $table->smallInteger('status_sinkron')->comment('0=belum, 1=sudah')->default(0);
            $table->date('tanggal_sinkron')->nullable();
            $table->smallInteger('user_id');
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
        Schema::dropIfExists('vaksinasis');
    }
}
