<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role', 20)->default('client')->after('email');
            $table->foreignId('owner_id')->nullable()->after('remember_token')->constrained('owners')->nullOnDelete();
            $table->foreignId('client_id')->nullable()->after('owner_id')->constrained('clients')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['owner_id']);
            $table->dropForeign(['client_id']);
            $table->dropColumn(['role', 'owner_id', 'client_id']);
        });
    }
};
