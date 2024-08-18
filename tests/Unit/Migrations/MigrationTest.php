<?php

namespace Eloise\DataAudit\Tests\Unit\Migrations;

use Eloise\DataAudit\Tests\TestCase;
use Illuminate\Support\Facades\Schema;

class MigrationTest extends TestCase
{
    public function test_database_tables()
    {
        $expectedTables = [
            'eloise_audit',
            'eloise_audit_action',
            'eloise_auditable_class',
            'test_eloise_auditable_model',
            'test_eloise_comment',
            'test_eloise_post',
            'users',
            'failed_jobs',
            'job_batches',
            'jobs',
            'migrations',
            'password_reset_tokens',
            'sessions',
        ];

        foreach ($expectedTables as $table) {
            $this->assertTrue(Schema::hasTable($table), "Table $table does not exist.");
        }
    }
}
