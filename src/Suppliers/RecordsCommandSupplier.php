<?php

namespace Eloise\RecordModel\Suppliers;

use Eloise\RecordModel\Constants\Headers;
use Eloise\RecordModel\Constants\Queries;
use Eloise\RecordModel\Queries\RecordQueries;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\table;

class RecordsCommandSupplier
{
    public function __construct(
        protected RecordQueries $recordQueries,
    ) {
    }
    public function getRecordsFromParameter(string $modelClassName, int|null $modelId, int|null $userId): bool|null
    {
        $dataFound = false;
        $this->recordQueries->getRecordFromUserAndModelId(
            $modelClassName,
            $modelId,
            $userId,
            function ($records) use (&$rows, &$dataFound) {
                $rows = [];
                foreach ($records as $record) {
                    $rows[] = $record->toArrayForTable();
                }
                table(
                    headers: Headers::RECORD_HEADERS_FOR_COMMAND,
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
