<?php

namespace Eloise\DataAudit\Suppliers;

use Eloise\DataAudit\Constants\Headers;
use Eloise\DataAudit\Constants\Queries;
use Eloise\DataAudit\Queries\AuditQueries;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\table;

class AuditsCommandSupplier
{
    public function __construct(
        protected AuditQueries $auditQueries,
    ) {
    }
    public function getAuditsFromParameter(string $modelClassName, int|null $modelId, int|null $userId): bool|null
    {
        $dataFound = false;
        $this->auditQueries->getAuditFromUserAndModelId(
            $modelClassName,
            $modelId,
            $userId,
            function ($audits) use (&$rows, &$dataFound) {
                $rows = [];
                foreach ($audits as $audit) {
                    $rows[] = $audit->toArrayForTable();
                }
                table(
                    headers: Headers::AUDIT_HEADERS_FOR_COMMAND,
                    rows: $rows
                );

                if (!$dataFound) {
                    $dataFound = true;
                }

                if (count($rows) < Queries::CHUNK_SIZE) {
                    return false;
                }

                if (!confirm('Do you want to load more data?', true)) {
                    return false;
                }
                return true;
            }
        );

        return $dataFound;
    }
}
