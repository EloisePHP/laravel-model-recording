<?php

namespace Eloise\DataAudit\Loaders;

use Eloise\DataAudit\Builders\ArrayFromAuditableContractBuilder;
use Eloise\DataAudit\Constants\Actions;
use Eloise\DataAudit\Contracts\AuditableModel;
use Eloise\DataAudit\Models\AuditableClass;
use Eloise\DataAudit\Models\AuditAction;
use Eloise\DataAudit\Services\AuditableModelContractChecker;
use Eloise\DataAudit\Services\RelationModelService;

class LoadActions
{
    public function __construct(
        protected AuditableModel|null $auditableModel,
    ) {
    }

    public function load(): void
    {
        $this->loadDefaultActions();

        $this->loadRelatedActions();
    }

    public function loadDefaultActions(): void
    {
        foreach (Actions::DEFAULT_ACTIONS as $action) {
            $this->updateOrCreateAction($this->auditableModel, ['action' => $action]);
        }
    }

    public function loadRelatedActions(): void
    {
        $checker = new AuditableModelContractChecker();
        $relationService = new RelationModelService();
        $allRelations = $relationService->getAllRelatedModels($this->auditableModel);

        $allRelations = $checker->arrayCheckerFilter($allRelations);

        foreach ($allRelations as $relation) {
            $options = [
                'action' => $relation['relation'],
                'target_class' => $relation['related_model'],
                'method' => $relation['method'],
            ];
            $this->updateOrCreateAction($this->auditableModel, $options);
        }
    }

    public function updateOrCreateAction(AuditableModel $auditableModel, array $options = []): void
    {
        $className = get_class($auditableModel);
        $auditableClass = AuditableClass::where(['class_name' => $className])->first();

        AuditAction::updateOrCreate(
            [
                'name' => $options['action'],
                'eloise_audit_class_id' => $auditableClass->id,
            ],
            [
                'description' => sprintf('%s%s', "Default action for ", $action = $options['action']),
                'version' => $auditableModel->versionAudit(),
                'source_class' => get_class($auditableModel),
                'target_class' => $options['target_class'] ?? '',
                'method' => $options['method'] ?? '',
            ]
        );
    }
}
