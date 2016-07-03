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
        return ['data' => $objectsModel->getAll()];
    }

    public function save(ObjectsModel $objectsModel, Request $request, Application $application)
    {
        if (!$request->isJson()) {
            $application->abort('415');
        }
        $requestData = $request->json()->all();
        $value = reset($requestData);
        $key = key($requestData);
        $object = [
            ObjectsModel::COLUMN_KEY => $key,
            ObjectsModel::COLUMN_VALUE => $value,
        ];
        $result = $objectsModel->save($object);
        return ['data' => $result];
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
