<?php

namespace App\Models;

use App\Events\ObjectSavedEvent;
use Illuminate\Database\Query\Builder;

class ObjectsModel extends Model
{
    const TABLE_NAME = 'objects';
    const COLUMN_ID = 'id';
    const COLUMN_KEY = 'key';
    const COLUMN_VALUE = 'value';
    const COLUMN_UPDATED_AT = 'updated_at';
    const COLUMN_CREATED_AT = 'created_at';

    public function insert(array $object)
    {
        $queryBuilder = $this->databaseManager->connection()->table($this::TABLE_NAME);
        $result = $queryBuilder->insert($object);
        $this->eventDispatcher->fire(new ObjectSavedEvent($object));
        return $result;
    }

    public function getByKey($key)
    {
        $queryBuilder = $this->databaseManager->connection()->table($this::TABLE_NAME);
        $queryBuilder->where($this::COLUMN_KEY, '=', $key);
        return $queryBuilder->first();
    }

    public function updateByKey($key, array $object)
    {
        $queryBuilder = $this->databaseManager->connection()->table($this::TABLE_NAME);
        $queryBuilder->where($this::COLUMN_KEY, '=', $key);
        $result = (bool)$queryBuilder->update($object);
        $this->eventDispatcher->fire(new ObjectSavedEvent($object));
        return $result;
    }

    public function getAll()
    {
        $queryBuilder = $this->databaseManager->connection()->table($this::TABLE_NAME);
        $queryBuilder->leftJoin(
            ObjectsLogsModel::TABLE_NAME,
            function ($join) {
                $join->on(
                    sprintf('%s.%s', ObjectsLogsModel::TABLE_NAME, ObjectsLogsModel::COLUMN_KEY),
                    '=',
                    sprintf('%s.%s', ObjectsModel::TABLE_NAME, ObjectsModel::COLUMN_KEY)
                );
                $join->on(
                    sprintf('%s.%s', ObjectsLogsModel::TABLE_NAME, ObjectsLogsModel::COLUMN_VALUE),
                    '!=',
                    sprintf('%s.%s', ObjectsModel::TABLE_NAME, ObjectsModel::COLUMN_VALUE)
                );
            }
        );
        $queryBuilder->groupBy(sprintf('%s.%s', ObjectsModel::TABLE_NAME, ObjectsModel::COLUMN_KEY));
        $queryBuilder->orderBy(sprintf('%s.%s', ObjectsModel::TABLE_NAME, ObjectsModel::COLUMN_ID));
        return $this->transformResult($queryBuilder->get($this->buildResultFields($queryBuilder)));
    }

    private function buildResultFields(Builder $queryBuilder)
    {
        return [
            sprintf('%s.%s', ObjectsModel::TABLE_NAME, ObjectsModel::COLUMN_KEY),
            sprintf('%s.%s', ObjectsModel::TABLE_NAME, ObjectsModel::COLUMN_VALUE),
            $queryBuilder->raw(
                sprintf('UNIX_TIMESTAMP(%s.%s) AS `timestamp`', ObjectsModel::TABLE_NAME, ObjectsModel::COLUMN_UPDATED_AT)
            ),
            $queryBuilder->raw(
                sprintf('DATE_FORMAT(%s.%s, \'%%Y-%%m-%%dT%%TZ\') AS `datetime`', ObjectsModel::TABLE_NAME, ObjectsModel::COLUMN_UPDATED_AT)
            ),
            $queryBuilder->raw(
                sprintf('GROUP_CONCAT(%s.%s) AS `values`', ObjectsLogsModel::TABLE_NAME, ObjectsLogsModel::COLUMN_VALUE)
            ),
            $queryBuilder->raw(sprintf(
                'GROUP_CONCAT(UNIX_TIMESTAMP(%s.%s)) AS `timestamps`',
                ObjectsLogsModel::TABLE_NAME,
                ObjectsLogsModel::COLUMN_CREATED_AT
            )),
        ];
    }

    private function transformResult(array $result = [])
    {
        foreach ($result as $index => &$object) {
            $values = $object['values'] ? explode(',', $object['values']) : [];
            $timestamps = $object['timestamps'] ? explode(',', $object['timestamps']) : [];
            unset($object['values'], $object['timestamps']);
            $object['logs'] = array_map(function ($timestamp, $value) {
                return compact('timestamp', 'value');
            }, $timestamps, $values);
        }
        return $result;
    }
}
