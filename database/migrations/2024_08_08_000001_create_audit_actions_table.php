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

        $this->addForeignKeysToAuditAction();
    }

    public function createAuditActionTable(): void
    {
        Schema::create('eloise_audit_action', function (Blueprint $table) {
            $table->id();
            $table->integer('eloise_audit_class_id');
            $table->string('name', 255);
            $table->string('source_class', 255);
            $table->string('target_class', 255)->nullable();
            $table->string('method', 255)->nullable();
            $table->string('description', 255)->nullable();
            $table->string('version', 255);
            $table->timestamps();
        });
    }

    public function addForeignKeysToAuditAction(): void
    {
        Schema::table('eloise_audit_action', static function (Blueprint $table): void {

            $table->foreign('eloise_audit_class_id')
                        ->references('id')->on('eloise_auditable_class')->onUpdate('cascade')->onDelete('cascade');
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
