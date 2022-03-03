<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePcrNakesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pcr_nakes', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal')->nullable();
            $table->integer('jumlah_tenaga_dokter_umum')->nullable();
            $table->integer('sudah_periksa_dokter_umum')->nullable();
            $table->integer('hasil_pcr_dokter_umum')->nullable();
            $table->integer('jumlah_tenaga_dokter_spesialis')->nullable();
            $table->integer('sudah_periksa_dokter_spesialis')->nullable();
            $table->integer('hasil_pcr_dokter_spesialis')->nullable();
            $table->integer('jumlah_tenaga_dokter_gigi')->nullable();
            $table->integer('sudah_periksa_dokter_gigi')->nullable();
            $table->integer('hasil_pcr_dokter_gigi')->nullable();
            $table->integer('jumlah_tenaga_residen')->nullable();
            $table->integer('sudah_periksa_residen')->nullable();
            $table->integer('hasil_pcr_residen')->nullable();
            $table->integer('jumlah_tenaga_perawat')->nullable();
            $table->integer('sudah_periksa_perawat')->nullable();
            $table->integer('hasil_pcr_perawat')->nullable();
            $table->integer('jumlah_tenaga_bidan')->nullable();
            $table->integer('sudah_periksa_bidan')->nullable();
            $table->integer('hasil_pcr_bidan')->nullable();
            $table->integer('jumlah_tenaga_apoteker')->nullable();
            $table->integer('sudah_periksa_apoteker')->nullable();
            $table->integer('hasil_pcr_apoteker')->nullable();
            $table->integer('jumlah_tenaga_radiografer')->nullable();
            $table->integer('sudah_periksa_radiografer')->nullable();
            $table->integer('hasil_pcr_radiografer')->nullable();
            $table->integer('jumlah_tenaga_analis_lab')->nullable();
            $table->integer('sudah_periksa_analis_lab')->nullable();
            $table->integer('hasil_pcr_analis_lab')->nullable();
            $table->integer('jumlah_tenaga_co_ass')->nullable();
            $table->integer('sudah_periksa_co_ass')->nullable();
            $table->integer('hasil_pcr_co_ass')->nullable();
            $table->integer('jumlah_tenaga_internship')->nullable();
            $table->integer('sudah_periksa_internship')->nullable();
            $table->integer('hasil_pcr_internship')->nullable();
            $table->integer('jumlah_tenaga_nakes_lainnya')->nullable();
            $table->integer('sudah_periksa_nakes_lainnya')->nullable();
            $table->integer('hasil_pcr_nakes_lainnya')->nullable();
            $table->integer('rekap_jumlah_tenaga')->nullable();
            $table->integer('rekap_jumlah_sudah_diperiksa')->nullable();
            $table->integer('rekap_jumlah_hasil_pcr')->nullable();
            $table->datetime('tgllapor')->nullable();

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
        Schema::dropIfExists('pcr_nakes');
    }
}
