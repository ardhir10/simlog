<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReturDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('retur_detail', function (Blueprint $table) {
            $table->id();
            $table->datetime('timestamp')->nullable();
            $table->integer('retur_id')->nullable();
            $table->integer('permintaan_barang_id')->nullable();
            $table->integer('permintaan_barang_detail_id')->nullable();
            $table->integer('barang_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->string('nama_barang')->nullable();
            $table->string('kode_barang')->nullable();
            $table->string('nomor_nota_dinas')->nullable();
            $table->string('nomor_upp3')->nullable();
            $table->string('nomor_upp4')->nullable();
            $table->float('jumlah_retur')->nullable();
            $table->string('status')->nullable()->default('');

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
        Schema::dropIfExists('retur_detail');
    }
}
