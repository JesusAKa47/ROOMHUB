<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('apartments', function (Blueprint $t) {
            $t->id();
            $t->string('title');
            $t->foreignId('owner_id')->constrained('owners'); // FK a owners
            $t->decimal('monthly_rent', 10, 2);
            $t->string('address');
            $t->decimal('lat', 10, 7)->nullable();
            $t->decimal('lng', 10, 7)->nullable();
            $t->date('available_from');                // date
            $t->boolean('is_furnished');               // radio
            $t->enum('status',['activo','inactivo'])->default('activo');
            $t->text('description');                   // textarea
            $t->json('photos')->nullable();            // JSON con rutas de fotos
            $t->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('apartments'); }
};
