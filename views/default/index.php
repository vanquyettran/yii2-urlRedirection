<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\modules\urlRedirection\models\UrlRedirection;

/* @var $this yii\web\View */
/* @var $searchModel \common\modules\urlRedirection\models\UrlRedirection */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Url Redirections';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="url-redirection-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Url Redirection', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'id',
            [
                'attribute' => 'type',
                'format' => 'raw',
                'value' => function (UrlRedirection $model) {
                    $types = UrlRedirection::getTypes();
                    return isset($types[$model->type]) ? $types[$model->type] : $model->type;
                },
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'type',
                    UrlRedirection::getTypes(),
                    ['class'=>'form-control', 'prompt' => '']
                )
            ],
            'from_url',
            'to_url',
            'response_code',
            'sort_order',
            'active:boolean',
            // 'create_time:datetime',
            // 'update_time:datetime',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
