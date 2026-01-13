<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\RmLabNapza */

$this->title = 'Pemeriksaan Rekam Medis '.$model->rm_id;
$this->params['breadcrumbs'][] = ['label' => 'Rm Lab Napzas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rm-lab-napza-view">

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->lab_napza_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->lab_napza_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::button('Cetak',['class'=>'btn btn-warning pull-right','onclick'=>'printElem_detail();']); ?>

    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'rm_id',
            'rm.mr0.mr',
            'rm.mr0.nama',
            'rm.mr0.no_identitas',
            'rm.mr0.alamat',
            'rm.kunjungan.created',
            'nomor_surat',
            'tanggal_surat',
            'permintaan',
            'keperluan:ntext',
            // 'tanggal_periksa',
            [
                'attribute'=>'viewHasils',
                'format'=>'html'
            ],
            'created',
        ],
    ]) ?>

</div>

<script type="text/javascript">
    function printElem_detail()
    {
        $.get("<?= Url::to(['rm-lab-napza/cetak','id'=>$model->lab_napza_id]) ?>", function( my_var ) {
            w = window.open();
            w.document.write(my_var);
            w.document.close(); // necessary for IE >= 10
            w.focus(); // necessary for IE >= 10
            return true;

        });
        
    }
</script>