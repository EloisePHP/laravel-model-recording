<?php

namespace Eloise\DataAudit\Tests\Unit\Services;

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
    public const string PREFIX_CLASS_NAME_TEST = 'Eloise\\DataAudit\\Tests\\Fixtures\\Models\\';
    public const string DEFAULT_AUDITABLE_MODEL_TEST = 'DefaultAuditableModel';

    public function test_get_auditable_models(): void
    {
        $auditableModelsFromProjectService = $this->app->make(AuditableModelsFromProject::class);

        $auditableModels = $auditableModelsFromProjectService->getAuditableModels();

        $this->assertCount(2, $auditableModels);

        $this->assertEquals(self::PREFIX_CLASS_NAME_TEST . self::DEFAULT_AUDITABLE_MODEL_TEST, $auditableModels[0]['class_name']);
        $this->assertEquals(self::DEFAULT_AUDITABLE_MODEL_TEST, $auditableModels[0]['short_name']);
        $this->assertTrue((bool) $auditableModels[0]['default']);
        $this->assertTrue((bool) $auditableModels[0]['active']);
        $this->assertEquals('v1_0', $auditableModels[0]['version']);
        $this->assertEquals(self::PREFIX_CLASS_NAME_TEST . self::DEFAULT_AUDITABLE_MODEL_TEST, $auditableModels[0]['source_class']);
    }
}
