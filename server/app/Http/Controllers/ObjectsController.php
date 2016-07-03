<?php

namespace App\Http\Controllers;

use App\Models\ObjectsLogsModel;
use App\Models\ObjectsModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Laravel\Lumen\Application;

class ObjectsController extends Controller
{
    public function index(ObjectsModel $objectsModel)
    {
        return $objectsModel->getAll();
    }

    public function save(ObjectsModel $objectsModel, Request $request, Application $application, Carbon $now)
    {
        if (!$request->isJson()) {
            $application->abort('415');
        }
        $requestData = $request->json()->all();
        $value = reset($requestData);
        $key = key($requestData);
        $existObject = $objectsModel->getByKey($key);
        if (!empty($existObject) && $existObject[ObjectsModel::COLUMN_VALUE] === $value) {
            return ['status' => false];
        }

        $object = [
            ObjectsModel::COLUMN_KEY => $key,
            ObjectsModel::COLUMN_VALUE => $value,
        ];
        if (empty($existObject)) {
            $object[ObjectsModel::COLUMN_UPDATED_AT] = $now;
            $result = $objectsModel->insert($object);
        } else {
            $result = $objectsModel->updateByKey($key, $object);
        }
        return ['status' => $result];
    }

    public function show($key, ObjectsLogsModel $objectsLogsModel, Application $application, Request $request)
    {
        $timestamp = (int)$request->query->get('timestamp', '');
        $filter = $timestamp > 0 ? [ObjectsLogsModel::COLUMN_CREATED_AT => $timestamp] : [];
        $object = $objectsLogsModel->getByKey($key, $filter);
        if (empty($object)) {
            $application->abort('404');
        }
        return $object[ObjectsLogsModel::COLUMN_VALUE];
    }
}
