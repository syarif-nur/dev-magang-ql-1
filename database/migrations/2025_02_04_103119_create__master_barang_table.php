<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('master_barang', function (Blueprint $table) {
            $table->id();
            $table->string('nama_barang', 255);
            $table->text('img_url')->nullable();
            $table->integer('qty');
            $table->smallInteger('status')->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('master_barang');
    }
};
