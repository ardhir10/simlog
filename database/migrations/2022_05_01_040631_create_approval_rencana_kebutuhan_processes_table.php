<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApprovalRencanaKebutuhanProcessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('approval_rencana_kebutuhan_process', function (Blueprint $table) {
            $table->id();

            $table->datetime('timestamp');
            $table->integer('rencana_kebutuhan_id');

            $table->integer('user_peminta_id');
            $table->string('user_peminta_name');

            // $table->integer('role_to_id');
            $table->string('role_to_name');

            $table->string('type')->nullable();
            $table->string('status')->nullable();


            $table->integer('step')->nullable();


            $table->string('keterangan')->nullable();
            $table->string('tindak_lanjut')->nullable();
            $table->integer('approve_by_id')->nullable();
            $table->string('kategori')->nullable();
            $table->string('from_kadisnav')->nullable();

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
        Schema::dropIfExists('approval_rencana_kebutuhan_process');
    }
}
