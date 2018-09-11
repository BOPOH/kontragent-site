<?php

use common\models\User;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Transaction */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="transaction-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'invoice_id')->textInput() ?>
    <?php
        $users = User::find()->all();
        $items = ArrayHelper::map($users, 'id', 'username');
    ?>
    <?= $form->field($model, 'user_id')->dropDownList($items, ['prompt' => 'Select user']) ?>

    <?= $form->field($model, 'type')->dropDownList([
        $model::TYPE_DEPOSIT => 'Depost',
        $model::TYPE_WITHDRAWAL => 'Withdrawal',
    ]) ?>

    <?= $form->field($model, 'amount')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
