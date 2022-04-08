<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermintaanBarangDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permintaan_barang_detail', function (Blueprint $table) {
            $table->id();
            $table->integer('permintaan_barang_id');
            $table->integer('barang_persediaan_id');
            $table->float('jumlah');
            $table->text('berita_tambahan');

            // DATA BARANG
            $table->string('final_kategori')->nullable();
            $table->string('final_nama_barang')->nullable();
            $table->string('final_kode_barang')->nullable();
            $table->string('final_tahun_perolehan')->nullable();
            $table->string('final_satuan')->nullable();

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
        Schema::dropIfExists('permintaan_barang_detail');
    }
}
