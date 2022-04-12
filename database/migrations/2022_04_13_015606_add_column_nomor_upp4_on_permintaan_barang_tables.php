<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnNomorUpp4OnPermintaanBarangTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('permintaan_barang', function (Blueprint $table) {
            $table->string('nomor_upp4')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('permintaan_barang', function (Blueprint $table) {
            $table->dropColumn('nomor_upp4')->nullable();
        });
    }
}
