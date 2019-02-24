<?php

namespace app\controllers;


use app\Service\WorkerServiceException;
use yii\rest\Controller;
use app\Service\WorkerService;

class ApiController extends Controller
{
    public function actionData($symbol, $start, $end, $email)
    {
        $worker = new WorkerService();
        try{
            $data = $worker->getData($symbol, $start, $end, $email);
        }catch (WorkerServiceException $exception){
            $data['error'] = $exception->getMessage();
        };
        return $data;
    }
}