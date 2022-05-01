<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRencanaKebutuhansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rencana_kebutuhan', function (Blueprint $table) {
            $table->id();
            $table->integer('created_by')->nullable();
            $table->string('created_by_name')->nullable();
            $table->datetime('timestamp')->nullable();
            $table->string('nomor_rk')->nullable();
            $table->text('kegiatan')->nullable();
            $table->string('mak')->nullable();
            $table->string('pengguna')->nullable();
            $table->string('tahun_anggaran')->nullable();
            $table->string('status')->nullable();
            $table->boolean('is_draft')->nullable();
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
        Schema::dropIfExists('rencana_kebutuhan');
    }
}
