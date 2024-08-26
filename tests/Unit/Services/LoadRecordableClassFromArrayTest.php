<?php

namespace Eloise\RecordModel\Tests\Unit\Services;

use Eloise\RecordModel\Models\RecordedModel;
use Eloise\RecordModel\Models\RecordAction;
use Eloise\RecordModel\Loaders\LoadRecordableClass;
use Eloise\RecordModel\Constants\Actions;
use Eloise\RecordModel\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoadRecordableClassFromArrayTest extends TestCase
{
    use RefreshDatabase;

    public array $recordModel = [
        'class_name' => 'App\\Models\\User',
        'short_name' => 'User',
        'default' => true,
        'active' => true,
        'version' => 'v1_0',
        'source_class' => 'App\\Models\\User',
        'recordable_id' => null
    ];

    public function test_load_recordable_class_creates_or_updates_recordable_class()
    {
        $service = new LoadRecordableClass($this->recordModel);

        // Ensure the recordable class doesn't exist before the test
        $this->assertNull(RecordedModel::where('class_name', $this->recordModel['class_name'])->first());

        // Run the method to create or update the recordable class
        $this->recordModel = $service->load($this->recordModel);

        // Verify the recordable class was created
        $recordableClass = RecordedModel::where('class_name', $this->recordModel['class_name'])->first();
        $this->assertNotNull($recordableClass);
        $this->assertEquals('User', $recordableClass->name);
        $this->assertEquals('v1_0', $recordableClass->version);

        // Run the method again to test the update functionality
        $this->recordModel['version'] = 'v2_0';
        $this->recordModel = $service->load($this->recordModel);

        // Verify the recordable class was updated
        $recordableClass = RecordedModel::where('class_name', $this->recordModel['class_name'])->first();
        $this->assertEquals('v2_0', $recordableClass->version);
    }
}
