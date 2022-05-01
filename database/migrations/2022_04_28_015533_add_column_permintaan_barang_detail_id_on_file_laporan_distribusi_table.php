<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnPermintaanBarangDetailIdOnFileLaporanDistribusiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('file_laporan_distribusi', function (Blueprint $table) {
            $table->integer('permintaan_barang_detail_id')->nullable();
            $table->integer('barang_distribusi_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::table('file_laporan_distribusi', function (Blueprint $table) {
            $table->dropColumn('permintaan_barang_detail_id')->nullable();
            $table->dropColumn('barang_distribusi_id')->nullable();
        });
    }
}
