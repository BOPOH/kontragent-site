<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$form = ActiveForm::begin([
    'id' => 'import-form',
    'options' => ['class' => 'form-horizontal', 'enctype' => 'multipart/form-data'],
]) ?>
    <?= $form->field($model, 'file')->fileInput() ?>
    <?= Html::submitButton('Import', ['class' => 'btn btn-primary']) ?>
<?php ActiveForm::end() ?>
