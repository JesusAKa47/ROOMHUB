<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('clients', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('email')->unique();
            $t->string('phone', 20);
            $t->enum('gender',['hombre','mujer','otro'])->default('otro'); // select
            $t->boolean('is_verified')->default(false);                    // radio
            $t->text('bio');                                               // textarea
            $t->string('id_scan_path')->nullable();                        // file (pdf/jpg/png/webp)
            $t->date('birthdate')->nullable();                             // date
            $t->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('clients'); }
};
