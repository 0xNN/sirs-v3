<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRuangansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ruangans', function (Blueprint $table) {
            $table->id();
            $table->integer("id_tt")->nullable();
            $table->string("tt")->nullable();
            $table->string("ruang")->nullable();
            $table->string("kode_siranap")->nullable();
            $table->integer("jumlah_ruang")->nullable();
            $table->integer("jumlah")->nullable();
            $table->integer("terpakai")->nullable();
            $table->integer("terpakai_suspek")->nullable();
            $table->integer("terpakai_konfirmasi")->nullable();
            $table->integer("antrian")->nullable();
            $table->integer("prepare")->nullable();
            $table->string("prepare_plan")->nullable();
            $table->integer("kosong")->nullable();
            $table->integer("covid")->nullable();
            $table->integer("id_t_tt")->nullable();

            $table->string('ClassCode')->nullable();
            $table->string('ServiceUnitID')->nullable();
            $table->string('RoomID')->nullable();
            $table->smallInteger('butuh_sinkron_ulang')->nullable()->comment('0 = tidak, 1 = ya');
            $table->smallInteger('status_sinkron')->nullable();
            $table->integer('user_id');
            $table->string('nama_user');
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
        Schema::dropIfExists('ruangans');
    }
}
