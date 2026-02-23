<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('apartments', function (Blueprint $table) {
            $table->text('description')->nullable()->after('address');
            // Amenidades (qué incluye)
            $table->boolean('has_ac')->default(false)->after('is_furnished');
            $table->boolean('has_tv')->default(false)->after('has_ac');
            $table->boolean('has_wifi')->default(false)->after('has_tv');
            $table->boolean('has_kitchen')->default(false)->after('has_wifi');
            $table->boolean('has_parking')->default(false)->after('has_kitchen');
            $table->boolean('has_laundry')->default(false)->after('has_parking');
            $table->boolean('has_heating')->default(false)->after('has_laundry');
            $table->boolean('has_balcony')->default(false)->after('has_heating');
            // Políticas
            $table->boolean('pets_allowed')->default(false)->after('has_balcony');
            $table->boolean('smoking_allowed')->default(false)->after('pets_allowed');
        });
    }

    public function down(): void
    {
        Schema::table('apartments', function (Blueprint $table) {
            $table->dropColumn([
                'description',
                'has_ac', 'has_tv', 'has_wifi', 'has_kitchen', 'has_parking',
                'has_laundry', 'has_heating', 'has_balcony',
                'pets_allowed', 'smoking_allowed',
            ]);
        });
    }
};
