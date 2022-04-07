<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKapalNegarasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kapal_negara', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kapal')->nullable();
            $table->string('imo_number')->nullable();
            $table->string('call_sign')->nullable();
            $table->string('mmsi')->nullable();
            $table->text('keterangan')->nullable();
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
        Schema::dropIfExists('kapal_negara');
    }
}
