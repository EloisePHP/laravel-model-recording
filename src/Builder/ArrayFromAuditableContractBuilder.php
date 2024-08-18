<?php

namespace Eloise\DataAudit\Builder;

use Eloise\DataAudit\Contracts\AuditableModel;

class ArrayFromAuditableContractBuilder
{
    protected string $className;
    protected string $shortName;
    protected bool $default;
    protected bool $active;
    protected string $version;
    protected string $source;

    public function __construct(
        protected AuditableModel $auditableModel,
    ) {
        $className = get_class($auditableModel);
        $parts = explode('\\', $className);

        $this->className = get_class($auditableModel);
        $this->shortName = end($parts);
        $this->default = $auditableModel->defaultAudit();
        $this->active = $auditableModel->activeAudit();
        $this->version = $auditableModel->versionAudit();
        $this->source = $auditableModel->getSourceModelClass();
    }

    /**
     * @return array{
     *     class_name: string,
     *     short_name: string,
     *     default: bool,
     *     active: bool,
     *     version: string,
     *     source_class: string
     * }
     */
    public function toArray(): array
    {
        return [
            'class_name' => $this->className,
                'short_name' => $this->shortName,
                'default' => $this->default,
                'active' => $this->active,
                'version' => $this->version,
                'source_class' => $this->source,
        ];
    }
}
