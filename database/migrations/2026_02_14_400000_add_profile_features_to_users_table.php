<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar', 500)->nullable()->after('email');
            $table->boolean('privacy_show_name_public')->default(true)->after('locality');
            $table->boolean('privacy_show_location_public')->default(false)->after('privacy_show_name_public');
            $table->boolean('privacy_show_last_login')->default(false)->after('privacy_show_location_public');
            $table->string('locale', 10)->nullable()->after('privacy_show_last_login');
            $table->string('timezone', 50)->nullable()->after('locale');
            $table->string('stripe_customer_id', 255)->nullable()->after('timezone');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'avatar',
                'privacy_show_name_public',
                'privacy_show_location_public',
                'privacy_show_last_login',
                'locale',
                'timezone',
                'stripe_customer_id',
            ]);
        });
    }
};
