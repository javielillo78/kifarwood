<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('pedidos')) {
            Schema::create('pedidos', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->string('estado')->default('carrito'); // carrito, pendiente, pagado, etc.
                $table->decimal('total', 10, 2)->default(0);
                $table->timestamps();

                $table->index(['user_id','estado']);
            });
        } else {
            Schema::table('pedidos', function (Blueprint $table) {
                if (!Schema::hasColumn('pedidos', 'total')) {
                    $table->decimal('total', 10, 2)->default(0)->after('estado');
                }
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
