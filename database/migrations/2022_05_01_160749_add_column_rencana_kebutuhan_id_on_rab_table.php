<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnRencanaKebutuhanIdOnRabTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rab', function (Blueprint $table) {
            $table->integer('rencana_kebutuhan_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rab', function (Blueprint $table) {
            $table->dropColumn('rencana_kebutuhan_id')->nullable();
        });
    }
}
