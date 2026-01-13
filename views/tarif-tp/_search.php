<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\search\TarifTpSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tarif-tp-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'tariftp_no') ?>

    <?= $form->field($model, 'tariftp_nm') ?>

    <?= $form->field($model, 'insurance_cd') ?>

    <?= $form->field($model, 'kelas_cd') ?>

    <?= $form->field($model, 'tarif_total') ?>

    <?php // echo $form->field($model, 'trx_tarif_seqno') ?>

    <?php // echo $form->field($model, 'modi_id') ?>

    <?php // echo $form->field($model, 'tarif_tp') ?>

    <?php // echo $form->field($model, 'modi_datetime') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
