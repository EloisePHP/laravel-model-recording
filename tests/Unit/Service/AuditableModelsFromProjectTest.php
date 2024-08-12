<?php

namespace Eloise\DataAudit\Tests\Unit\Console\Commands;

use Eloise\DataAudit\Constants\PathNames;
use Eloise\DataAudit\Contracts\AuditableModel;
use Eloise\DataAudit\Services\AuditableModelsFromProject;
use Illuminate\Support\Facades\Artisan;
use Eloise\DataAudit\Tests\TestCase;
use Eloise\DataAudit\Traits\AuditableModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use ReflectionClass;

class AuditableModelsFromProjectTest extends TestCase
{
    public const string MODELS_FOLDER_TEST = '/../../../../../tests/Fixtures/Models';
    public const string PREFIX_CLASS_NAME_TEST = 'Eloise\\DataAudit\\Tests\\Fixtures\\Models\\';
    public const string DEFAULT_AUDITABLE_MODEL_TEST = 'DefaultAuditableModel';

    public function test_get_auditable_models(): void
    {
        // This is a route to get to the TestModels (Orchestra is a laravel project inside a package so paths functions stay in that package)
        Config::set('paths.models_folder', self::MODELS_FOLDER_TEST);
        Config::set('paths.prefix_class_name', self::PREFIX_CLASS_NAME_TEST);

        $auditableModelsFromProjectService = $this->app->make(AuditableModelsFromProject::class);
        
        $auditableModels = $auditableModelsFromProjectService->getAuditableModels();

        $this->assertCount(1, $auditableModels);

        $this->assertEquals(self::PREFIX_CLASS_NAME_TEST.self::DEFAULT_AUDITABLE_MODEL_TEST, $auditableModels[0]['class_name']);
        $this->assertEquals(self::DEFAULT_AUDITABLE_MODEL_TEST, $auditableModels[0]['short_name']);
        $this->assertTrue($auditableModels[0]['default']);
        $this->assertTrue($auditableModels[0]['active']);
        $this->assertEquals('v1_0', $auditableModels[0]['version']);
        $this->assertEquals(self::PREFIX_CLASS_NAME_TEST.self::DEFAULT_AUDITABLE_MODEL_TEST, $auditableModels[0]['source_class']);

    }
}
