<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('eloise_recorded_model', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('class_name');
            $table->string('version');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('eloise_recorded_model');
    }
};
