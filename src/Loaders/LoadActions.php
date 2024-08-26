<?php

namespace Eloise\RecordModel\Loaders;

use Eloise\RecordModel\Constants\Actions;
use Eloise\RecordModel\Contracts\RecordableModel;
use Eloise\RecordModel\Models\RecordedModel;
use Eloise\RecordModel\Models\RecordAction;
use Eloise\RecordModel\Services\RecordableModelContractChecker;
use Eloise\RecordModel\Services\RelationModelService;

class LoadActions
{
    public function __construct(
        protected RecordableModel|null $recordableModel,
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
            $this->updateOrCreateAction($this->recordableModel, ['action' => $action]);
        }
    }

    public function loadRelatedActions(): void
    {
        $checker = new RecordableModelContractChecker();
        $relationService = new RelationModelService();
        $allRelations = $relationService->getAllRelatedModels($this->recordableModel);

        $allRelations = $checker->arrayCheckerFilter($allRelations);

        foreach ($allRelations as $relation) {
            $options = [
                'action' => $relation['relation'],
                'target_class' => $relation['related_model'],
                'method' => $relation['method'],
            ];
            $this->updateOrCreateAction($this->recordableModel, $options);
        }
    }

    /**
     * @param array{
     *     action: string,
     *     target_class?: string,
     *     method?: string
     * } $options
     */
    public function updateOrCreateAction(RecordableModel $recordableModel, array $options = ['action' => '']): void
    {
        $className = get_class($recordableModel);
        $recordableClass = RecordedModel::where(['class_name' => $className])->first();

        RecordAction::updateOrCreate(
            [
                'name' => $options['action'],
                'eloise_record_class_id' => $recordableClass->id,
            ],
            [
                'description' => sprintf('%s%s', "Default action for ", $options['action']),
                'version' => $recordableModel->versionRecord(),
                'source_class' => get_class($recordableModel),
                'target_class' => $options['target_class'] ?? '',
                'method' => $options['method'] ?? '',
            ]
        );
    }
}
