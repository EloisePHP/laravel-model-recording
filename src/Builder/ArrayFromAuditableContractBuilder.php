<?php

namespace Eloise\DataAudit\Builder;

use Eloise\DataAudit\Contracts\AuditableModel;

class ArrayFromAuditableContractBuilder
{
    protected string $className;
    protected string $shortName;
    protected string $default;
    protected string $active;
    protected string $version;
    protected string $source;
   
    public function __construct(
        protected AuditableModel $auditableModel,
    )
    {
        $className = get_class($auditableModel);
        $parts = explode('\\', $className);
        
        $this->className = get_class($auditableModel);
        $this->shortName = end($parts);
        $this->default = $auditableModel->defaultAudit();
        $this->active = $auditableModel->activeAudit();
        $this->version = $auditableModel->versionAudit();
        $this->source = $auditableModel->getSourceModelClass();
    }

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
