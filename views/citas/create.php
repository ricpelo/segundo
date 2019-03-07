<?php

use yii\helpers\Url;
use yii\helpers\Html;

use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Citas */

$this->title = 'Create Citas';
$this->params['breadcrumbs'][] = ['label' => 'Citas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$url = Url::to(['citas/especialistas-ajax']);
$urlHueco = Url::to(['citas/hueco-ajax']);
$js = <<<EOT
    hueco();
    $('#citas-especialidad_id').change(function (ev) {
        var el = $(this);
        var especialidad_id = el.val();
        $.ajax({
            url: '$url',
            data: { especialidad_id: especialidad_id },
            success: function (data) {
                var sel = $('#citas-especialista_id');
                sel.empty();
                for (i in data) {
                    var option = document.createElement('option');
                    option.value = data[i].id;
                    option.innerHTML = data[i].nombre;
                    sel.append(option);
                }
                hueco();
            }
        });
    });
    $('#citas-especialista_id').change(function (ev) {
        hueco();
    });
    function hueco() {
        var el = $('#citas-especialista_id');
        var especialista_id = el.val();
        $.ajax({
            url: '$urlHueco',
            data: { especialista_id: especialista_id },
            success: function (data) {
                $('#citas-instante-oculto').val(data.valor);
                $('#citas-instante').val(data.formateado);
            }
        });
    }
EOT;
$this->registerJs($js);
?>
<div class="citas-create">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="citas-form">
        <?php $form = ActiveForm::begin(['enableAjaxValidation' => true]); ?>
            <?= $form->field($model, 'especialidad_id')->dropDownList($especialidades) ?>
            <?= $form->field($model, 'especialista_id')->dropDownList($especialistas) ?>
            <?= Html::activeHiddenInput($model, 'instante', ['id' => 'citas-instante-oculto']) ?>
            <?= $form->field($model, 'instante')->textInput(['id' => 'citas-instante','readonly' => true, 'name' => '']) ?>
            <div class="form-group">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
            </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
