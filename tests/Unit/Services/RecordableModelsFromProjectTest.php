<?php

namespace Eloise\RecordModel\Tests\Unit\Services;

use Eloise\RecordModel\Constants\PathNames;
use Eloise\RecordModel\Contracts\RecordableModel;
use Eloise\RecordModel\Services\RecordableModelsFromProject;
use Illuminate\Support\Facades\Artisan;
use Eloise\RecordModel\Tests\TestCase;
use Eloise\RecordModel\Traits\RecordableModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use ReflectionClass;

class RecordableModelsFromProjectTest extends TestCase
{
    public const string PREFIX_CLASS_NAME_TEST = 'Eloise\\RecordModel\\Tests\\Fixtures\\Models\\';
    public const string DEFAULT_RECORDABLE_MODEL_TEST = 'DefaultRecordableModel';

    public function test_get_recordable_models(): void
    {
        $recordableModelsFromProjectService = $this->app->make(RecordableModelsFromProject::class);

        $recordableModels = $recordableModelsFromProjectService->toArray();

        $this->assertCount(self::AMOUNT_OF_RECORDABLE_MODELS, $recordableModels);

        $this->assertEquals(self::PREFIX_CLASS_NAME_TEST . self::DEFAULT_RECORDABLE_MODEL_TEST, $recordableModels[0]['class_name']);
        $this->assertEquals(self::DEFAULT_RECORDABLE_MODEL_TEST, $recordableModels[0]['short_name']);
        $this->assertEquals('v1_0', $recordableModels[0]['version']);
        $this->assertEquals(self::PREFIX_CLASS_NAME_TEST . self::DEFAULT_RECORDABLE_MODEL_TEST, $recordableModels[0]['source_class']);
    }
}
