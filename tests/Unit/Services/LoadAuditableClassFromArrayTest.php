<?php

namespace Eloise\DataAudit\Tests\Unit\Services;

use Eloise\DataAudit\Models\AuditableClass;
use Eloise\DataAudit\Models\AuditAction;
use Eloise\DataAudit\Loaders\LoadAuditableClass;
use Eloise\DataAudit\Constants\Actions;
use Eloise\DataAudit\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoadAuditableClassFromArrayTest extends TestCase
{
    use RefreshDatabase;

    public array $auditModel = [
        'class_name' => 'App\\Models\\User',
        'short_name' => 'User',
        'default' => true,
        'active' => true,
        'version' => 'v1_0',
        'source_class' => 'App\\Models\\User',
        'auditable_id' => null
    ];

    public function test_load_auditable_class_creates_or_updates_auditable_class()
    {
        $service = new LoadAuditableClass($this->auditModel);

        // Ensure the auditable class doesn't exist before the test
        $this->assertNull(AuditableClass::where('class_name', $this->auditModel['class_name'])->first());

        // Run the method to create or update the auditable class
        $this->auditModel = $service->load($this->auditModel);

        // Verify the auditable class was created
        $auditableClass = AuditableClass::where('class_name', $this->auditModel['class_name'])->first();
        $this->assertNotNull($auditableClass);
        $this->assertEquals('User', $auditableClass->name);
        $this->assertEquals('v1_0', $auditableClass->version);

        // Run the method again to test the update functionality
        $this->auditModel['version'] = 'v2_0';
        $this->auditModel = $service->load($this->auditModel);

        // Verify the auditable class was updated
        $auditableClass = AuditableClass::where('class_name', $this->auditModel['class_name'])->first();
        $this->assertEquals('v2_0', $auditableClass->version);
    }
}
