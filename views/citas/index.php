<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CitasSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Citas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="citas-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Citas', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'summary' => '',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'especialista.especialidad.especialidad',
            'especialista.nombre',
            [
                'attribute' => 'instante',
                'format' => 'datetime',
                'filter' => Html::activeInput(
                    'datetime-local',
                    $searchModel,
                    'instante',
                    ['class' => 'form-control']
                )
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'delete' => function ($url, $model, $key) {
                        return Html::a(
                            'Anular',
                            ['citas/delete', 'id' => $model->id],
                            [
                                'data-method' => 'POST',
                                'data-confirm' => '¿Seguro que desea anular la cita?',
                            ]
                        );
                    }
                ],
            ],
        ],
    ]); ?>


</div>
