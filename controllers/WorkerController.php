<?php

namespace app\controllers;


use yii\web\Controller;

class WorkerController extends Controller
{
    public $layout = 'worker';

    public function actionIndex()
    {
        return $this->render('index');
    }
}