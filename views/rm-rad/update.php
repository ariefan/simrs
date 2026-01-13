<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\RmLab */

$this->title = 'Update Pencatatan Hasil Pemeriksaan Laboratorium';
$this->params['breadcrumbs'][] = ['label' => 'Rm Labs', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="rm-lab-update">

    <?= 
    	$this->render('_form', compact('model','data_lab','pasien','data_dokter','dokter')) 
    ?>

</div>
