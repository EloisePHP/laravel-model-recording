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
        Schema::create('eloise_audit', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()
                    ->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('eloise_audit_action_id')->nullable()
                    ->constrained('eloise_audit_action')->onUpdate('cascade')->onDelete('cascade');
            $table->string('action');
            $table->string('source_class', 255);
            $table->unsignedBigInteger('source_id');
            $table->string('target_class', 255)->nullable();
            $table->unsignedBigInteger('target_id')->nullable();
            $table->string('message', 255)->nullable();
            $table->string('version', 255)->nullable();
            $table->json('diff')->nullable();
            $table->string('link', 255)->nullable();
            $table->timestamps(6);
            $table->json('serialized_data')->nullable();
        });

        Schema::table('eloise_audit', function (Blueprint $table) {
            // Adding indexes
            $table->index('user_id');
            $table->index(['source_id', 'source_class']);
            $table->index(['target_id', 'target_class']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('eloise_audit', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['eloise_audit_action_id']);

            $table->dropIndex(['user_id']);
            $table->dropIndex(['source_id', 'source_class']);
            $table->dropIndex(['target_id', 'target_class']);
        });

        Schema::dropIfExists('eloise_audit');
    }
};
