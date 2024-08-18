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
        $this->createAuditTable();
        $this->addIndexesToAuditTable();
        $this->addForeignKeysToAudit();
    }

    public function createAuditTable(): void
    {
        Schema::create('eloise_audit', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->integer('eloise_audit_action_id')->nullable();
            $table->string('action');
            $table->string('source_class', 255);
            $table->integer('source_id');
            $table->string('target_class', 255)->nullable();
            $table->integer('target_id')->nullable();
            $table->string('message', 255)->nullable();
            $table->string('version', 255)->nullable();
            $table->json('changes', 255)->nullable();
            $table->string('link', 255)->nullable();
            $table->timestamps();
            $table->json('serialized_data')->nullable();
        });
    }

    public function addIndexesToAuditTable(): void
    {
        Schema::table('eloise_audit', function (Blueprint $table) {
            $table->index('user_id');

            $table->index(['source_id', 'source_class']);
            $table->index(['target_id', 'target_class']);
        });
    }

    public function addForeignKeysToAudit(): void
    {
        Schema::table('eloise_audit', static function (Blueprint $table): void {

            $table->foreign('user_id')
                        ->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('eloise_audit_action_id')
                        ->references('id')->on('eloise_audit_action')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('eloise_audit', function (Blueprint $table) {
            $table->dropIndex(['user_id']);

            $table->dropIndex(['source_id', 'source_class']);
            $table->dropIndex(['target_id', 'target_class']);
        });
        Schema::dropIfExists('eloise_audit');
    }
};
