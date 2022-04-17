<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnLaporByIdOnLaporanDistribusiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('laporan_distribusi', function (Blueprint $table) {
            $table->integer('lapor_by_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('laporan_distribusi', function (Blueprint $table) {
            $table->dropColumn('lapor_by_id')->nullable();
        });
    }
}
