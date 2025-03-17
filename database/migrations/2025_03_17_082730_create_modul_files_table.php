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
        Schema::create('modul_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('modul_id');
            $table->string('filepath');
            $table->timestamps();

            $table->foreign('modul_id')->references('id')->on('moduls');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modul_files');
    }
};
