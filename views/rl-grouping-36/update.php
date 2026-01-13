<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\RlGrouping36 */

$this->title = 'Grouping RL 3.6 - '.$model->spesialisasi;
$this->params['breadcrumbs'][] = ['label' => 'Daftar Grouping 3.6', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="rl-grouping36-update">

    <?= $this->render('_form',compact('tindakan','grouping','model','jenis_exist','tindakan_exist')) ?>

</div>
