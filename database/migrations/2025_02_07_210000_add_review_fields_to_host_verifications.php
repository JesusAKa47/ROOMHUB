<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('host_verifications', function (Blueprint $table) {
            $table->text('rejection_reason')->nullable()->after('status');
            $table->timestamp('reviewed_at')->nullable()->after('rejection_reason');
        });

        $driver = Schema::getConnection()->getDriverName();
        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE host_verifications MODIFY COLUMN status ENUM('pendiente', 'en_revision', 'aprobado', 'rechazado') NOT NULL DEFAULT 'pendiente'");
        }
    }

    public function down(): void
    {
        Schema::table('host_verifications', function (Blueprint $table) {
            $table->dropColumn(['rejection_reason', 'reviewed_at']);
        });

        $driver = Schema::getConnection()->getDriverName();
        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE host_verifications MODIFY COLUMN status ENUM('pendiente', 'aprobado', 'rechazado') NOT NULL DEFAULT 'pendiente'");
        }
    }
};
