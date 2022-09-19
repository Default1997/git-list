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
    /**
     * Запуск метода для периодической проверки
     */

    public function actionIndex() {
        Yii::$app->checkComponent->checkUpdate();
    }

}