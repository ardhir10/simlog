<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnOnUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('kapal_negara_id')->nullable();
            $table->integer('stasiun_vts_id')->nullable();
            $table->integer('srop_id')->nullable();
            $table->string('keterangan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('kapal_negara_id')->nullable();
            $table->dropColumn('stasiun_vts_id')->nullable();
            $table->dropColumn('srop_id')->nullable();
            $table->dropColumn('keterangan')->nullable();
        });
    }
}
