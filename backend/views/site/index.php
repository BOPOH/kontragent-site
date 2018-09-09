<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Test Application';
?>
<div class="site-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'username',
            'email:email',
             [
                'attribute' => 'invoice_id',
                'label' => 'Balance',
                'format' => 'text', // raw, html
                'content' => function($data) {
                    return $data->invoice ? $data->invoice->balance : 0;
                },
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
                'urlCreator' => function ($action, $model, $key, $index) {
                    return Url::to(['user/'.$action, 'id' => $model->id]);
                },
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
