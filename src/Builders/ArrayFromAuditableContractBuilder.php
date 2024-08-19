<?php

namespace Eloise\DataAudit\Builders;

use Eloise\DataAudit\Contracts\AuditableModel;

class ArrayFromAuditableContractBuilder
{
    protected string $className;
    protected string $shortName;
    protected string $version;
    protected string $source;

    public function __construct(
        protected AuditableModel $auditableModel,
    ) {
        $className = get_class($auditableModel);
        $parts = explode('\\', $className);

        $this->className = get_class($auditableModel);
        $this->shortName = end($parts);
        $this->version = $auditableModel->versionAudit();
        $this->source = $auditableModel->getSourceModelClass();
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
