<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\TransactionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Transactions');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transaction-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute' => 'invoice_id',
                'label' => 'Invoice',
                'format' => 'html', // raw, html
                'content' => function($data) {
                    return Html::a(sprintf('Invoice #%s', $data->invoice_id), ['invoice/view', 'id' => $data->invoice_id]);
                },
            ],
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
            [
                'attribute' => 'user_id',
                'label' => 'User',
                'format' => 'html', // raw, html
                'content' => function($data) {
                    return Html::a($data->user->username, ['user/view', 'id' => $data->user->id]);
                },
            ],
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
