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
    Schema::table('owners', function (Blueprint $table) {
        // Estado de verificación
        $table->enum('verification_status', ['en_revision', 'verificado', 'rechazado'])
              ->default('en_revision')
              ->after('is_active');

        // Campos de autenticación
        $table->string('password')->nullable()->after('email');
        $table->rememberToken();
    });
}

public function down(): void
{
    Schema::table('owners', function (Blueprint $table) {
        $table->dropColumn(['verification_status', 'password', 'remember_token']);
    });
}

};
