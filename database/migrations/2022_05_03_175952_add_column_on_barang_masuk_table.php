<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnOnBarangMasukTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('barang_masuk', function (Blueprint $table) {
            $table->text('keterangan')->nullable();
            $table->integer('created_by')->nullable();
            $table->string('from')->default('bp')->nullable();
            $table->integer('transfer_from')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('barang_masuk', function (Blueprint $table) {
            $table->dropColumn('keterangan')->nullable();
            $table->dropColumn('created_by')->nullable();
            $table->dropColumn('from')->nullable();
            $table->dropColumn('transfer_from')->nullable();
        });
    }
}
