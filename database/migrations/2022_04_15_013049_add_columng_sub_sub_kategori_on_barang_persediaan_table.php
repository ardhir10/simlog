<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumngSubSubKategoriOnBarangPersediaanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('barang_persediaan', function (Blueprint $table) {
            $table->string('sub_sub_kategori')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('barang_persediaan', function (Blueprint $table) {
            $table->dropColumn('sub_sub_kategori')->nullable();
        });
    }
}
