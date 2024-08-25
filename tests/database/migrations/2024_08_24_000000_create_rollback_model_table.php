<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        $this->createRollbackModelTestTable();
    }

    public function createRollbackModelTestTable(): void
    {
        Schema::create('test_eloise_rollback_model', function (Blueprint $table) {
            $table->id();
            $table->string('test_name');
            $table->string('test_description');
            $table->integer('test_int');
            $table->json('test_array');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('test_eloise_rollback_model');
    }
};
