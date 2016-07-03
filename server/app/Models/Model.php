<?php

namespace App\Models;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\DatabaseManager;

class Model
{
    protected $databaseManager;
    protected $eventDispatcher;

    public function __construct(DatabaseManager $databaseManager, Dispatcher $dispatcher)
    {
        $this->eventDispatcher = $dispatcher;
        $this->databaseManager = $databaseManager;
        $this->databaseManager->connection()->setFetchMode(\PDO::FETCH_ASSOC);
    }
}
