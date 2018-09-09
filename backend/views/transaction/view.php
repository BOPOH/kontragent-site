<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Transaction */

$this->title = sprintf(Yii::t('app', 'Transaction #%s for invoice #%s'), $model->id, $model->invoice->id);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Transactions'), 'url' => ['transaction/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transaction-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'invoice_id',
                'label' => 'Invoice',
                'format' => 'html',
                'value' => function($data) {
                    return Html::a(sprintf('Invoice #%s', $data->invoice_id), ['invoice/view', 'id' => $data->invoice_id]);
                },
            ],
            'stamp',
             [
                'attribute' => 'type',
                'label' => 'Type',
                'format' => 'text', // raw, html
                'value' => function($data) {
                    return $data->typeName;
                },
            ],
            'amount',
            [
                'attribute' => 'user_id',
                'label' => 'User',
                'format' => 'html', // raw, html
                'value' => function($data) {
                    return Html::a($data->user->username, ['user/view', 'id' => $data->user->id]);
                },
            ],
            'balance_after',
        ],
    ]) ?>

</div>
