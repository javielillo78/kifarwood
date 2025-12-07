<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nif', 20)->nullable()->after('email');
            $table->string('telefono', 20)->nullable()->after('nif');

            $table->string('direccion', 255)->nullable()->after('telefono');
            $table->string('cp', 10)->nullable()->after('direccion');
            $table->string('ciudad', 120)->nullable()->after('cp');
            $table->string('provincia', 120)->nullable()->after('ciudad');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'nif',
                'telefono',
                'direccion',
                'cp',
                'ciudad',
                'provincia',
            ]);
        });
    }
};
