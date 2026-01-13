<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\RmLab */

$this->title = 'Pencatatan Hasil Pemeriksaan Laboratorium';
$this->params['breadcrumbs'][] = ['label' => 'Rm Labs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rm-lab-create">

    <?= $this->render('_form', compact('model','item_lab','pasien','data_dokter','rm_lab','data_lab','dokter')) ?>

</div>
