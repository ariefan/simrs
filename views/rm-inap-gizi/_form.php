<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\RmInapGizi */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="rm-inap-gizi-form">

    <?php $form = ActiveForm::begin(['id'=>'rm-inap-gizi','action'=>Url::toRoute(['rm-inap-gizi/create','rm_id'=>$model->rm_id])]); ?>
<label>Gizi</label>
    <?= \yii\redactor\widgets\Redactor::widget([
                                    'model' => $model,
                                    'attribute' => 'kode_diet',
                                    'clientOptions'=>[
                                        // 'buttons' => []
                                    ]
            ]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Tambah' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
