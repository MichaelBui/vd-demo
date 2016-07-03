<?php

namespace App\Models;

use Carbon\Carbon;

class ObjectsLogsModel extends Model
{
    const TABLE_NAME = 'objects_logs';
    const COLUMN_ID = 'id';
    const COLUMN_KEY = 'key';
    const COLUMN_VALUE = 'value';
    const COLUMN_CREATED_AT = 'created_at';

    public function getByKey($key, array $filter = [])
    {
        $queryBuilder = $this->databaseManager->connection()->table($this::TABLE_NAME);
        $queryBuilder->where($this::COLUMN_KEY, '=', $key);
        $queryBuilder->orderBy($this::COLUMN_CREATED_AT, 'desc');
        if (array_key_exists($this::COLUMN_CREATED_AT, $filter)) {
            $datetime = Carbon::createFromTimestamp($filter[$this::COLUMN_CREATED_AT], 'UTC');
            $queryBuilder->where($this::COLUMN_CREATED_AT, '<=', $datetime);
        }
        return $queryBuilder->first();
    }
}
