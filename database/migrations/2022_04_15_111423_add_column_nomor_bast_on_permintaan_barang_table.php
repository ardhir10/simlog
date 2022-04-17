<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnNomorBastOnPermintaanBarangTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('permintaan_barang', function (Blueprint $table) {
            $table->datetime('bast_at')->nullable();
            $table->string('nomor_bast')->nullable();
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
            $table->dropColumn('bast_at')->nullable();
            $table->dropColumn('nomor_bast')->nullable();
        });
    }
}
