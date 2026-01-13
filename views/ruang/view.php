<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Ruang */

$this->title = $model->ruang_cd;
$this->params['breadcrumbs'][] = ['label' => 'Ruangs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ruang-view">

    <p>
        <?= Html::a('Ubah', ['update', 'id' => $model->ruang_cd], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Hapus', ['delete', 'id' => $model->ruang_cd], [
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
            'ruang_cd',
            'kelas_cd',
            'bangsal_cd',
            'ruang_nm',
            'status',
        ],
    ]) ?>

</div>
