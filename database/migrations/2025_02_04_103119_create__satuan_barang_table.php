<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('satuan_barang', function (Blueprint $table) {
            $table->id();
            $table->integer('id_barang');
            $table->string('nama_satuan', 255);
            $table->float('harga', 10, 2);
            $table->smallInteger('status')->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('satuan_barang');
    }
};
