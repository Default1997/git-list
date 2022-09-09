<?php

namespace app\commands;

use yii\console\Controller;
use app\models\GitUser;

/**
 * Check controller
 */
class CheckController extends Controller {

    public function actionIndex() {
        GitUser::checkUpdate();
    }

    public function actionMail($to) {
        echo "Sending mail to " . $to;
    }

}