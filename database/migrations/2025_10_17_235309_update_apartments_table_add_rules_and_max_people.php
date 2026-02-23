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
    Schema::table('apartments', function (Blueprint $table) {
        // Cambiar 'description' a 'rules' (VARCHAR)
        $table->renameColumn('description', 'rules');
        $table->string('rules', 1000)->change();

        // Agregar campo max_people
        $table->unsignedSmallInteger('max_people')->default(1)->after('is_furnished');
    });
}

public function down(): void
{
    Schema::table('apartments', function (Blueprint $table) {
        $table->renameColumn('rules', 'description');
        $table->text('description')->change();
        $table->dropColumn('max_people');
    });
}

};
