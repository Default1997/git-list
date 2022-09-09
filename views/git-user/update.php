<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\GitUser $model */

$this->title = 'Update Git User: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Git Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="git-user-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
