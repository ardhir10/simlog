<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBarangPersediaansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('barang_persediaan', function (Blueprint $table) {
            $table->id();

            // EXISTING
            $table->string('sumber_barang')->nullable();
            $table->integer('kategori_barang_id')->nullable();
            $table->string('nama_barang')->nullable();
            $table->string('kode_barang')->nullable();
            $table->string('tahun_perolehan')->nullable();
            $table->integer('jumlah')->nullable();
            $table->integer('satuan_id')->nullable();
            $table->float('harga_perolehan')->nullable();
            $table->string('mata_uang')->nullable();
            $table->string('masa_simpan')->nullable();
            $table->integer('jumlah_stok_minimal')->nullable();
            $table->text('spesifikasi_barang')->nullable();
            $table->string('foto_barang')->nullable();


            // BARANG BARU
            $table->string('nomor_bast')->nullable();
            $table->string('dokumen_bast')->nullable();



            $table->integer('created_by_id')->nullable();
            $table->string('created_by_name')->nullable();

            $table->integer('updated_by_id')->nullable();
            $table->string('updated_by_name')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('barang_persediaan');
    }
}
