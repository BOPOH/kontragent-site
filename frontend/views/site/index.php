<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\TransactionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $user common\models\User */
$user = Yii::$app->user->identity;

$this->title = 'My Yii Application';
?>
<div class="site-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(
                sprintf(Yii::t('app', 'Your balance: %s'), $user->invoice ? $user->invoice->balance : 0),
                ['invoice/view', 'id' => $user->invoice ? $user->invoice->id : null]
            ) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute' => 'type',
                'label' => 'Type',
                'format' => 'text', // raw, html
                'content' => function($data) {
                    return $data->typeName;
                },
                'filter' => [$searchModel::TYPE_DEPOSIT => 'Deposit', $searchModel::TYPE_WITHDRAWAL => 'Withdrawal'],
            ],
            'amount',
            'balance_after',
            [
                'attribute' => 'stamp',
                'value' => 'stamp',
                'filter' => \yii\jui\DatePicker::widget([
                    'model'=> $searchModel,
                    'attribute'=>'stamp',
                    'dateFormat' => 'yyyy-MM-dd',
                ]),
                'format' => 'html',
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
                'urlCreator' => function ($action, $model, $key, $index) {
                    return Url::to(['transaction/'.$action, 'id' => $model->id]);
                },
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
