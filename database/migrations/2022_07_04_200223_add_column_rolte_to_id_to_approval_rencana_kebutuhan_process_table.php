<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnRolteToIdToApprovalRencanaKebutuhanProcessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('approval_rencana_kebutuhan_process', function (Blueprint $table) {
            $table->integer('role_to_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('approval_rencana_kebutuhan_process', function (Blueprint $table) {
            $table->dropColumn('role_to_id');
        });
    }
}
