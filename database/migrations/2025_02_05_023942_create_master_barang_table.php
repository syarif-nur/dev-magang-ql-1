<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('master_barang', function (Blueprint $table) {
            $table->id(); // bigserial
            $table->string('nama_barang', 255); // collation default
            $table->text('img_url');   // txt, collation default
            $table->integer('qty');    // int4
            $table->smallInteger('status')->default(1);  // int2, default '1' smallint
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_barang');
    }
};
