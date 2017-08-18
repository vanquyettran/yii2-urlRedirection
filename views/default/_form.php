<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\modules\urlRedirection\models\UrlRedirection;

/* @var $this yii\web\View */
/* @var $model \common\modules\urlRedirection\models\UrlRedirection */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="url-redirection-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'type')->dropDownList(UrlRedirection::getTypes()) ?>

    <?= $form->field($model, 'from_url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'to_url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'response_code')->dropDownList(UrlRedirection::getResponseCodes()) ?>

    <?= $form->field($model, 'active')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
