<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('owners', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('email')->unique();
            $t->string('phone', 20);
            $t->enum('type', ['persona','empresa'])->default('persona');
            $t->boolean('is_active')->default(true);
            $t->string('avatar_path')->nullable(); // archivo (imagen)
            $t->text('notes');                     // textarea
            $t->date('since')->default(now());     // date
            $t->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('owners'); }
};
