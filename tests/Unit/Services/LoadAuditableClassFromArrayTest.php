<?php

namespace Eloise\DataAudit\Tests\Unit\Services;

use Eloise\DataAudit\Models\AuditableClass;
use Eloise\DataAudit\Models\AuditAction;
use Eloise\DataAudit\Services\LoadAuditableClassFromArray;
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
        $service = new LoadAuditableClassFromArray($this->auditModel);

        // Ensure the auditable class doesn't exist before the test
        $this->assertNull(AuditableClass::where('class_name', $this->auditModel['class_name'])->first());

        // Run the method to create or update the auditable class
        $this->auditModel = $service->loadAuditableClass($this->auditModel);

        // Verify the auditable class was created
        $auditableClass = AuditableClass::where('class_name', $this->auditModel['class_name'])->first();
        $this->assertNotNull($auditableClass);
        $this->assertEquals('User', $auditableClass->name);
        $this->assertTrue((bool) $auditableClass->default);
        $this->assertTrue((bool) $auditableClass->active);
        $this->assertEquals('v1_0', $auditableClass->version);

        // Run the method again to test the update functionality
        $this->auditModel['version'] = 'v2_0';
        $this->auditModel = $service->loadAuditableClass($this->auditModel);

        // Verify the auditable class was updated
        $auditableClass = AuditableClass::where('class_name', $this->auditModel['class_name'])->first();
        $this->assertEquals('v2_0', $auditableClass->version);
    }

    public function test_load_default_actions_creates_or_updates_audit_actions()
    {
        $service = new LoadAuditableClassFromArray($this->auditModel);

        // First, load the auditable class to set the auditable_id
        $this->auditModel = $service->loadAuditableClass($this->auditModel);

        // Now load the default actions
        $service->loadActions($this->auditModel);

        // Verify that the default actions were created
        foreach (Actions::DEFAULT_ACTIONS as $action) {
            $auditAction = AuditAction::where('name', $action)
                ->where('eloise_audit_class_id', $this->auditModel['auditable_id'])
                ->first();

            $this->assertNotNull($auditAction);
            $this->assertEquals('Default action for ' . Actions::ACTION_CREATED, $auditAction->description);
            $this->assertEquals('v1_0', $auditAction->version);
            $this->assertEquals('App\\Models\\User', $auditAction->source_class);
            $this->assertEquals('App\\Models\\User', $auditAction->target_class);
        }
    }
}
