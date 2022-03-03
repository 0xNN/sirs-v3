<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSDMSTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('s_d_m_s', function (Blueprint $table) {
            $table->id();
            $table->integer('id_kebutuhan')->nullable();
            $table->string('kebutuhan')->nullable();
            $table->integer('jumlah_eksisting')->nullable();
            $table->integer('jumlah')->nullable();
            $table->integer('jumlah_diterima')->nullable();
            $table->datetime('tglupdate')->nullable();

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
        Schema::dropIfExists('s_d_m_s');
    }
}
