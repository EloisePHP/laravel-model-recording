<?php

namespace Eloise\RecordModel\Tests\Unit\Migrations;

use Eloise\RecordModel\Tests\TestCase;
use Illuminate\Support\Facades\Schema;

class MigrationTest extends TestCase
{
    public function test_database_tables()
    {
        $expectedTables = [
            'eloise_record',
            'eloise_record_action',
            'eloise_recorded_model',
            'test_eloise_recordable_model',
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
