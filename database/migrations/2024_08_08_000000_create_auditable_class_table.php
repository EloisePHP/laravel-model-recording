<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('eloise_auditable_class', function (Blueprint $table) {
            $table->id(); // Uses bigIncrements, which is compatible with all databases
            $table->string('name'); // Default length (255) is compatible across all databases
            $table->string('class_name');
            $table->string('version');
            $table->timestamps(); // Adds created_at and updated_at with the appropriate precision
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('eloise_auditable_class');
    }
};
