<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Ubicación del usuario para recomendar por localidad, ciudad o municipio.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('postal_code', 10)->nullable()->after('last_login_at');
            $table->string('state', 100)->nullable()->after('postal_code');
            $table->string('city', 100)->nullable()->after('state');
            $table->string('municipality', 100)->nullable()->after('city');
            $table->string('locality', 150)->nullable()->after('municipality');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['postal_code', 'state', 'city', 'municipality', 'locality']);
        });
    }
};
