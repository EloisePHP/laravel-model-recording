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
        $this->createAuditActionTable();
    }

    public function createAuditActionTable(): void
    {
        Schema::create('eloise_audit_action', function (Blueprint $table) {
            $table->id();
            $table->foreignId('eloise_audit_class_id')->constrained('eloise_auditable_class')
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
        Schema::dropIfExists('eloise_audit_action');
    }
};
