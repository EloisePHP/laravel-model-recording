<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->createAuditableClassTable();

    }

    public function createAuditableClassTable(): void
    {
        Schema::create('eloise_auditable_class', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('class_name', 255);
            $table->boolean('default');
            $table->boolean('active');
            $table->string('version', 255);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('eloise_auditable_class');
    }
};
