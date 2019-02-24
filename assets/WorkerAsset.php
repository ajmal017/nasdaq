<?php
/**
 * Created by PhpStorm.
 * User: Мария
 * Date: 23.02.2019
 * Time: 17:56
 */

namespace app\assets;


use yii\web\AssetBundle;

class WorkerAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        '//cdn.jsdelivr.net/chartist.js/latest/chartist.min.css'
    ];
    public $js = [
        '//cdn.jsdelivr.net/chartist.js/latest/chartist.min.js',
        'js/worker.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}