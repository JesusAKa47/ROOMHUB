<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('apartments', function (Blueprint $table) {
            $table->json('rules_new')->nullable()->after('status');
        });

        $apartments = DB::table('apartments')->get();
        foreach ($apartments as $apt) {
            $rules = $apt->rules;
            $arr = [];
            if ($rules !== null && $rules !== '') {
                $decoded = json_decode($rules, true);
                if (is_array($decoded)) {
                    $arr = array_values(array_filter($decoded, function ($v) { return is_string($v) && trim($v) !== ''; }));
                } else {
                    $lines = array_filter(array_map('trim', explode("\n", (string) $rules)));
                    $arr = empty($lines) ? [trim((string) $rules)] : array_values($lines);
                }
            }
            DB::table('apartments')->where('id', $apt->id)->update(['rules_new' => json_encode($arr)]);
        }

        Schema::table('apartments', function (Blueprint $table) {
            $table->dropColumn('rules');
        });
        Schema::table('apartments', function (Blueprint $table) {
            $table->json('rules')->nullable()->after('status');
        });
        foreach (DB::table('apartments')->get() as $apt) {
            DB::table('apartments')->where('id', $apt->id)->update(['rules' => $apt->rules_new]);
        }
        Schema::table('apartments', function (Blueprint $table) {
            $table->dropColumn('rules_new');
        });
    }

    public function down(): void
    {
        Schema::table('apartments', function (Blueprint $table) {
            $table->text('rules_old')->nullable()->after('status');
        });
        foreach (DB::table('apartments')->get() as $apt) {
            $rules = $apt->rules;
            $str = '';
            if ($rules !== null) {
                $decoded = is_string($rules) ? json_decode($rules, true) : $rules;
                if (is_array($decoded)) {
                    $str = implode("\n", $decoded);
                }
            }
            DB::table('apartments')->where('id', $apt->id)->update(['rules_old' => $str]);
        }
        Schema::table('apartments', function (Blueprint $table) {
            $table->dropColumn('rules');
        });
        Schema::table('apartments', function (Blueprint $table) {
            $table->text('rules')->nullable()->after('status');
        });
        foreach (DB::table('apartments')->get() as $apt) {
            DB::table('apartments')->where('id', $apt->id)->update(['rules' => $apt->rules_old]);
        }
        Schema::table('apartments', function (Blueprint $table) {
            $table->dropColumn('rules_old');
        });
    }
};
