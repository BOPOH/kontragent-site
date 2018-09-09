<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Transaction */

$this->title = sprintf(Yii::t('app', '%s transaction #%s'), $model->user->username, $model->id);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Transactions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transaction-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'invoice_id',
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
            'balance_after',
        ],
    ]) ?>

</div>
