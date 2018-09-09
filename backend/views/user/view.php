<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = sprintf('%s (#%s)', $model->username, $model->id);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <p>
        <?= Html::a(Yii::t('app', 'User\'s transaction'), ['/transaction/user', 'id' => $model->ID], ['class' => 'btn btn-primary']) ?>
        <?php if ($model->invoice): ?>
        <?= Html::a(Yii::t('app', 'Invoice info'), ['/invoice/view', 'id' => $model->invoice->id], ['class' => 'btn btn-primary']) ?>
        <?php endif; ?>
    </p>


    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'username',
            'email:email',
            'invoice.balance',
        ],
    ]) ?>

</div>
