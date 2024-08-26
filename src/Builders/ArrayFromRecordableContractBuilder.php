<?php

namespace Eloise\RecordModel\Builders;

use Eloise\RecordModel\Contracts\RecordableModel;

class ArrayFromRecordableContractBuilder
{
    protected string $className;
    protected string $shortName;
    protected string $version;
    protected string $source;

    public function __construct(
        protected RecordableModel $recordableModel,
    ) {
        $className = get_class($recordableModel);
        $parts = explode('\\', $className);

        $this->className = get_class($recordableModel);
        $this->shortName = end($parts);
        $this->version = $recordableModel->versionRecord();
        $this->source = $recordableModel->getSourceModelClass();
    }

    /**
     * @return array{
     *     class_name: string,
     *     short_name: string,
     *     version: string,
     *     source_class: string
     * }
     */
    public function toArray(): array
    {
        return [
            'class_name' => $this->className,
                'short_name' => $this->shortName,
                'version' => $this->version,
                'source_class' => $this->source,
        ];
    }
}
