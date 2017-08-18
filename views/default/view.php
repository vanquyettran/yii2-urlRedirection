<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model \common\modules\urlRedirection\models\UrlRedirection */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Url Redirections', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="url-redirection-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'creator_id',
            'updater_id',
            'from_url:ntext',
            'to_url:url',
            'active',
            'type',
            'status',
            'sort_order',
            'create_time:datetime',
            'update_time:datetime',
        ],
    ]) ?>

</div>
