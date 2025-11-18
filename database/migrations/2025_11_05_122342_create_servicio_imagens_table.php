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
        if (!Schema::hasTable('servicio_imagens')) {
            Schema::create('servicio_imagens', function (Illuminate\Database\Schema\Blueprint $table) {
                $table->id();
                $table->foreignId('servicio_id')->constrained('servicios')->cascadeOnDelete();
                $table->string('ruta');
                $table->unsignedInteger('orden')->default(1);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servicio_imagens');
    }
};
