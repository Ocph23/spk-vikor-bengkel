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
        Schema::create('kriterias', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('variabel')->unique();
            $table->string('keterangan');
            $table->integer('bobot')->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kriterias');
    }
};
