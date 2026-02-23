<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('host_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('ine_photo_path');
            $table->json('answers'); // respuestas a preguntas legales
            $table->enum('status', ['pendiente', 'aprobado', 'rechazado'])->default('pendiente');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();

            $table->unique('user_id'); // un usuario solo puede tener una verificación
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('host_verifications');
    }
};
