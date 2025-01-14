<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('questionnaire_id')->constrained()->cascadeOnDelete();
            $table->string('session_id')->unique();
            $table->string('ip_address');
            $table->string('user_agent');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('responses');
    }
};
