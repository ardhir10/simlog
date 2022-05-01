<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnJumlahDisetujuiOnRencanaKebutuhanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rencana_kebutuhan_detail', function (Blueprint $table) {
            $table->float('jumlah_disetujui')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rencana_kebutuhan_detail', function (Blueprint $table) {
            $table->dropColumn('jumlah_disetujui')->nullable();
        });
    }
}
