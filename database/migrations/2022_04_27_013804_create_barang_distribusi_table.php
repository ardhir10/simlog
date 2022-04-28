<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBarangDistribusiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('barang_distribusi', function (Blueprint $table) {
            $table->id();
            $table->integer('permintaan_id');
            $table->integer('permintaan_barang_detail_id');
            $table->integer('laporan_distribusi_id');
            $table->datetime('timestamp');
            $table->float('jumlah');
            $table->string('file');
            $table->integer('user_id');
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
        Schema::dropIfExists('barang_distribusi');
    }
}
