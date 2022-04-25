<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApprovalRabsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('approval_rab', function (Blueprint $table) {
            $table->id();
            $table->datetime('timestamp');
            $table->integer('rab_id');
            $table->integer('user_id');
            $table->string('user_name');
            $table->string('role_to_name');
            $table->string('type');
            $table->string('status');
            $table->text('keterangan');
            $table->string('tindak_lanjut');
            $table->integer('approve_by_id');
            $table->string('kategori');
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
        Schema::dropIfExists('approval_rab');
    }
}
