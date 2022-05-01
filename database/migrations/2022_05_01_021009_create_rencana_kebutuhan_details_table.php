<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRencanaKebutuhanDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rencana_kebutuhan_detail', function (Blueprint $table) {
            $table->id();
            $table->integer('rencana_kebutuhan_id');
            $table->integer('barang_id')->nullable();
            $table->string('nama_barang')->nullable();
            $table->string('satuan')->nullable();
            $table->string('pengguna')->nullable();
            $table->float('qty')->nullable();
            $table->float('harga_satuan')->nullable();
            $table->string('mata_uang')->nullable();
            $table->text('keterangan')->nullable();
            $table->integer('add_by')->nullable();
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
        Schema::dropIfExists('rencana_kebutuhan_detail');
    }
}
