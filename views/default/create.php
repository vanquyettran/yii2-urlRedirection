<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model \common\modules\urlRedirection\models\UrlRedirection */

$this->title = 'Create Url Redirection';
$this->params['breadcrumbs'][] = ['label' => 'Url Redirections', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="url-redirection-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
