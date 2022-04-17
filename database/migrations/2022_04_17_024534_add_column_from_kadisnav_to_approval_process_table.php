<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnFromKadisnavToApprovalProcessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('approval_process', function (Blueprint $table) {
            $table->string('from_kadisnav')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('approval_process', function (Blueprint $table) {
            $table->dropColumn('from_kadisnav')->nullable();

        });
    }
}
