<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermintaanBarangsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permintaan_barang', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->boolean('is_draft')->nullable();

            $table->string('nomor_nota_dinas')->nullable()->unique();
            $table->string('nomor_upp3')->nullable()->unique();
            $table->datetime('tanggal_permintaan')->nullable();
            $table->text('perihal')->nullable();

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
        Schema::dropIfExists('permintaan_barang');
    }
}
