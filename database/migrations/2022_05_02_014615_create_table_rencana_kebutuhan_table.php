<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableRencanaKebutuhanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rencana_kebutuhan_tahunan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_barang')->nullable();
            $table->string('kategori')->nullable();
            $table->string('p01')->nullable();
            $table->string('p02')->nullable();
            $table->string('p03')->nullable();
            $table->string('p04')->nullable();
            $table->string('p05')->nullable();
            $table->string('p06')->nullable();
            $table->string('p07')->nullable();
            $table->string('p08')->nullable();
            $table->string('p09')->nullable();
            $table->string('p10')->nullable();
            $table->string('p11')->nullable();
            $table->string('p12')->nullable();
            $table->string('tahun')->nullable();
            $table->string('created_by')->nullable();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('rencana_kebutuhan_tahunan');
    }
}
