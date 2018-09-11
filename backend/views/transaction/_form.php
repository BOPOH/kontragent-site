<?php

use common\models\User;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\web\JsExpression;

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
    <?=
        // $form->field($model, 'user_id')->dropDownList($items, ['prompt' => 'Select user'])
        $form->field($model, 'user_id')->widget(Select2::classname(), [
            'options' => ['placeholder' => 'Search for a user ...'],
            'pluginOptions' => [
                'allowClear' => true,
                'minimumInputLength' => 3,
                'language' => [
                    'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                ],
                'ajax' => [
                    'url' => Url::to(['user/search']),
                    'dataType' => 'json',
                    'data' => new JsExpression('function(params) { return {q:params.term}; }')
                ],
                'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                'templateResult' => new JsExpression('function(user) { return user.username; }'),
                'templateSelection' => new JsExpression('function (user) { return user.username; }'),
            ],
        ]);
    ?>

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
