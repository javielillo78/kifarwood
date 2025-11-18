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
        if (!Schema::hasTable('imagenes')) {
            Schema::create('imagenes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('producto_id')
                    ->constrained('productos')
                    ->cascadeOnUpdate()
                    ->cascadeOnDelete();
                $table->string('ruta', 255);
                $table->unsignedInteger('orden')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('imagenes');
    }
};
