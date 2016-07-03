<?php

namespace App\Listeners;

use App\Events\ObjectSavedEvent;
use App\Models\ObjectsLogsModel;
use App\Models\ObjectsModel;
use Illuminate\Database\DatabaseManager;

class GenerateObjectLogListener
{
    private $databaseManager;

    public function __construct(DatabaseManager $databaseManager)
    {
        $this->databaseManager = $databaseManager;
    }

    public function handle(ObjectSavedEvent $event)
    {
        $queryBuilder = $this->databaseManager->connection()->table(ObjectsLogsModel::TABLE_NAME);
        $object = $event->getData();
        $objectLog = [
            ObjectsLogsModel::COLUMN_KEY => $object[ObjectsModel::COLUMN_KEY],
            ObjectsLogsModel::COLUMN_VALUE => $object[ObjectsModel::COLUMN_VALUE],
        ];
        $queryBuilder->insert($objectLog);
    }
}
