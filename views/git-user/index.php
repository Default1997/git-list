<?php

use app\models\GitUser;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\GitUserSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Git Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="git-user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Git User', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'username',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, GitUser $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>

    <table class="table">
        <?php foreach ($list as $item): ?>
            <tr>
                <td>
                    <?= Html::tag('a', Html::encode($item->name), ['href' => Url::to($item->link)]); ?> 
                </td>
                <td>
                    <?=Html::encode(date('d.m.Y h:i:s', $item->updated_at)); ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
