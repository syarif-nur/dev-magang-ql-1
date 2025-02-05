<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_barang')->constrained('master_barang')->onDelete('cascade');
            $table->foreignId('id_satuan')->constrained('satuan_barang')->onDelete('cascade');
            $table->integer('qty');
            $table->decimal('total_harga', 10, 2);
            $table->integer('id_customer');
            $table->smallInteger('status')->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transaksi');
    }
};
