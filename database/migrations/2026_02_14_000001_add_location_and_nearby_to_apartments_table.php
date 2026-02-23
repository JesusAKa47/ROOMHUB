<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('apartments', function (Blueprint $table) {
            $table->string('state', 100)->nullable()->after('address')->comment('Entidad federativa / Estado');
            $table->string('city', 100)->nullable()->after('state');
            $table->string('municipality', 100)->nullable()->after('city');
            $table->string('locality', 150)->nullable()->after('municipality');
            $table->json('nearby')->nullable()->after('locality')->comment('Cercanías: universidad, hospital, etc. [{ "tipo": "...", "nombre": "..." }]');
        });
    }

    public function down(): void
    {
        Schema::table('apartments', function (Blueprint $table) {
            $table->dropColumn(['state', 'city', 'municipality', 'locality', 'nearby']);
        });
    }
};
