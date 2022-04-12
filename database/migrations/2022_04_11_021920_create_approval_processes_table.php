<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApprovalProcessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('approval_process', function (Blueprint $table) {
            $table->id();

            $table->datetime('timestamp');
            $table->integer('permintaan_barang_id');

            $table->integer('user_peminta_id');
            $table->string('user_peminta_name');

            // $table->integer('role_to_id');
            $table->string('role_to_name');

            $table->string('type')->nullable();
            $table->string('status')->nullable();


            $table->integer('step')->nullable();


            $table->string('keterangan')->nullable();







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
        Schema::dropIfExists('approval_process');
    }
}
