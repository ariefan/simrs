<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\RmInap */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="rm-inap-form">

    <?php $form = ActiveForm::begin(); ?>

<label>Anamnesis (Subyekif)</label>
     <?= \yii\redactor\widgets\Redactor::widget([
                                    'model' => $model,
                                    'attribute' => 'anamnesis',
                                    'clientOptions'=>[
                                        // 'buttons' => []
                                    ]
            ]) ?>
<label>Pemeriksaan Fisik (Obyektif)</label>
     <?= \yii\redactor\widgets\Redactor::widget([
                                    'model' => $model,
                                    'attribute' => 'pemeriksaan_fisik',
                                    'clientOptions'=>[
                                        // 'buttons' => []
                                    ]
            ]) ?>
<label>Assesment</label>           
     <?= \yii\redactor\widgets\Redactor::widget([
                                    'model' => $model,
                                    'attribute' => 'assesment',
                                    'clientOptions'=>[
                                        // 'buttons' => []
                                    ]
            ]) ?>
<label>Plan</label>
     <?= \yii\redactor\widgets\Redactor::widget([
                                    'model' => $model,
                                    'attribute' => 'plan',
                                    'clientOptions'=>[
                                        // 'buttons' => []
                                    ]
            ]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
