<?php

namespace app\commands;

use yii\console\Controller;
// use app\models\GitUser;
use yii;
// use app\components\mycomponent;

/**
 * Check controller
 */
class CheckController extends Controller {

    public function actionIndex() {
        Yii::$app->mycomponent->checkUpdate();
    }

}