<?php

namespace App\Models;

use App\Events\ObjectSavedEvent;

class ObjectsModel extends Model
{
    const TABLE_NAME = 'objects';
    const COLUMN_ID = 'id';
    const COLUMN_KEY = 'key';
    const COLUMN_VALUE = 'value';
    const COLUMN_UPDATED_AT = 'updated_at';
    const COLUMN_CREATED_AT = 'created_at';

    public function save(array $objectData)
    {
        $queryBuilder = $this->databaseManager->connection()->table($this::TABLE_NAME);
        $result = $queryBuilder->updateOrInsert([$this::COLUMN_KEY => $objectData[$this::COLUMN_KEY]], $objectData);
        $this->eventDispatcher->fire(new ObjectSavedEvent($objectData));
        return $result;
    }

    public function getAll()
    {
        $queryBuilder = $this->databaseManager->connection()->table($this::TABLE_NAME);
        return $queryBuilder->get();
    }
}
