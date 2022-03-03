<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCovidsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('covids', function (Blueprint $table) {
            $table->id();
            $table->integer('id_laporan')->comment('api rs online')->nullable();
            $table->string('kewarganegaraanId', 10)->nullable();
            $table->string('nik', 20)->nullable();
            $table->string('noPassport', 50)->nullable();
            $table->smallInteger('asalPasienId')->nullable();
            $table->string('noRM', 50)->nullable();
            $table->string('namaLengkapPasien', 120);
            $table->string('namaInisialPasien', 100)->nullable();
            $table->date('tanggalLahir');
            $table->string('email', 60)->nullable();
            $table->string('noTelp', 20)->nullable();
            $table->string('jenisKelaminId', 2);
            $table->integer('domisiliKecamatanId')->nullable();
            $table->integer('domisiliKabKotaId')->nullable();
            $table->integer('domisiliProvinsiId')->nullable();
            $table->smallInteger('pekerjaanId')->nullable();
            $table->date('tanggalMasuk')->nullable();
            // $table->smallInteger('statusVaksinasiId')->nullable();
            $table->smallInteger('jenisPasienId')->nullable();
            $table->smallInteger('statusPasienId')->nullable();
            // $table->smallInteger('varianCovidId')->nullable();
            // $table->smallInteger('severityLevelId')->nullable();
            // $table->smallInteger('statusKomorbidId')->comment('0=tidak, 1=ya')->nullable();
            $table->smallInteger('statusCoInsidenId')->comment('0=tidak, 1=ya')->nullable();
            $table->smallInteger('statusRawatId')->nullable();
            // $table->smallInteger('saturasiOksigen')->comment('> 95, 90 - 95, 80 - 89, < 80')->nullable();
            $table->smallInteger('alatOksigenId')->nullable();
            $table->smallInteger('penyintasId')->comment('0=tidak, 1=ya')->nullable();
            $table->date('tanggalOnsetGejala')->nullable();
            $table->integer('kelompokGejalaId')->nullable();
            // Gejala
            $table->smallInteger('demamId')->comment('0=tidak, 1=ya')->nullable();
            $table->smallInteger('batukId')->comment('0=tidak, 1=ya')->nullable();
            $table->smallInteger('pilekId')->comment('0=tidak, 1=ya')->nullable();
            $table->smallInteger('sakitTenggorokanId')->comment('0=tidak, 1=ya')->nullable();
            $table->smallInteger('sesakNapasId')->comment('0=tidak, 1=ya')->nullable();
            $table->smallInteger('lemasId')->comment('0=tidak, 1=ya')->nullable();
            $table->smallInteger('nyeriOtotId')->comment('0=tidak, 1=ya')->nullable();
            $table->smallInteger('mualMuntahId')->comment('0=tidak, 1=ya')->nullable();
            $table->smallInteger('diareId')->comment('0=tidak, 1=ya')->nullable();
            $table->smallInteger('anosmiaId')->comment('0=tidak, 1=ya')->nullable();
            $table->smallInteger('napasCepatId')->comment('0=tidak, 1=ya')->nullable();
            $table->smallInteger('frekNapas30KaliPerMenitId')->comment('0=tidak, 1=ya')->nullable();
            $table->smallInteger('distresPernapasanBeratId')->comment('0=tidak, 1=ya')->nullable();
            $table->smallInteger('lainnyaId')->comment('0=tidak, 1=ya')->nullable();

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
        Schema::dropIfExists('covids');
    }
}
