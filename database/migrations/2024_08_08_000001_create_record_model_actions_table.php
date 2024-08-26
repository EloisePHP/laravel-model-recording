<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $this->createRecordActionTable();
    }

    public function createRecordActionTable(): void
    {
        Schema::create('eloise_record_action', function (Blueprint $table) {
            $table->id();
            $table->foreignId('eloise_recorded_model_id')->constrained('eloise_recorded_model')
                    ->onUpdate('cascade')->onDelete('cascade');
            $table->string('name');
            $table->string('source_class');
            $table->string('target_class')->nullable();
            $table->string('method')->nullable();
            $table->string('description')->nullable();
            $table->string('version');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eloise_record_action');
    }
};
