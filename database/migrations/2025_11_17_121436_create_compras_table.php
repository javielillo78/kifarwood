<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('compras')) {
            Schema::create('compras', function (Blueprint $table) {
                $table->id();
                $table->foreignId('producto_id')
                    ->constrained('productos')
                    ->cascadeOnUpdate()
                    ->restrictOnDelete();
                $table->foreignId('user_id')
                    ->nullable()
                    ->constrained('users')
                    ->cascadeOnUpdate()
                    ->restrictOnDelete();
                $table->integer('unidades');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('compras');
    }
};
