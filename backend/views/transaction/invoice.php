<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $invoice common\models\Invoice */
/* @var $searchModel common\models\TransactionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = sprintf(Yii::t('app', '%s\'s invoice #%s transactions'), $invoice->user->username, $invoice->id);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Transactions'), 'url' => ['transaction/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transaction-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>

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
