<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNakesTerinfeksisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nakes_terinfeksis', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->integer('co_ass')->nullable();
            $table->integer('residen')->nullable();
            $table->integer('intership')->nullable();
            $table->integer('dokter_spesialis')->nullable();
            $table->integer('dokter_umum')->nullable();
            $table->integer('dokter_gigi')->nullable();
            $table->integer('perawat')->nullable();
            $table->integer('bidan')->nullable();
            $table->integer('apoteker')->nullable();
            $table->integer('radiografer')->nullable();
            $table->integer('analis_lab')->nullable();
            $table->integer('nakes_lainnya')->nullable();
            $table->integer('co_ass_meninggal')->nullable();
            $table->integer('residen_meninggal')->nullable();
            $table->integer('intership_meninggal')->nullable();
            $table->integer('dokter_spesialis_meninggal')->nullable();
            $table->integer('dokter_umum_meninggal')->nullable();
            $table->integer('dokter_gigi_meninggal')->nullable();
            $table->integer('perawat_meninggal')->nullable();
            $table->integer('bidan_meninggal')->nullable();
            $table->integer('apoteker_meninggal')->nullable();
            $table->integer('radiografer_meninggal')->nullable();
            $table->integer('analis_lab_meninggal')->nullable();
            $table->integer('nakes_lainnya_meninggal')->nullable();
            $table->integer('co_ass_dirawat')->nullable();
            $table->integer('residen_dirawat')->nullable();
            $table->integer('intership_dirawat')->nullable();
            $table->integer('dokter_spesialis_dirawat')->nullable();
            $table->integer('dokter_umum_dirawat')->nullable();
            $table->integer('dokter_gigi_dirawat')->nullable();
            $table->integer('perawat_dirawat')->nullable();
            $table->integer('bidan_dirawat')->nullable();
            $table->integer('apoteker_dirawat')->nullable();
            $table->integer('radiografer_dirawat')->nullable();
            $table->integer('analis_lab_dirawat')->nullable();
            $table->integer('nakes_lainnya_dirawat')->nullable();
            $table->integer('co_ass_isoman')->nullable();
            $table->integer('residen_isoman')->nullable();
            $table->integer('intership_isoman')->nullable();
            $table->integer('dokter_spesialis_isoman')->nullable();
            $table->integer('dokter_umum_isoman')->nullable();
            $table->integer('dokter_gigi_isoman')->nullable();
            $table->integer('perawat_isoman')->nullable();
            $table->integer('bidan_isoman')->nullable();
            $table->integer('apoteker_isoman')->nullable();
            $table->integer('radiografer_isoman')->nullable();
            $table->integer('analis_lab_isoman')->nullable();
            $table->integer('nakes_lainnya_isoman')->nullable();
            $table->integer('co_ass_sembuh')->nullable();
            $table->integer('residen_sembuh')->nullable();
            $table->integer('intership_sembuh')->nullable();
            $table->integer('dokter_spesialis_sembuh')->nullable();
            $table->integer('dokter_umum_sembuh')->nullable();
            $table->integer('dokter_gigi_sembuh')->nullable();
            $table->integer('perawat_sembuh')->nullable();
            $table->integer('bidan_sembuh')->nullable();
            $table->integer('apoteker_sembuh')->nullable();
            $table->integer('radiografer_sembuh')->nullable();
            $table->integer('analis_lab_sembuh')->nullable();
            $table->integer('nakes_lainnya_sembuh')->nullable();

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
        Schema::dropIfExists('nakes_terinfeksis');
    }
}
