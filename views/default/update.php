<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model \common\modules\urlRedirection\models\UrlRedirection */

$this->title = 'Update Url Redirection: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Url Redirections', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="url-redirection-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
