<?php 
use app\models\Kunjungan;
use app\models\RekamMedis;
use yii\helpers\Url;
use yii\helpers\Html;
$histori_rm = Kunjungan::find()->rightJoin('rekam_medis','rekam_medis.kunjungan_id=kunjungan.kunjungan_id')->where(['rekam_medis.mr'=>$kunjungan->mr0->mr])->all();
?>

<p>Histori Pemeriksaan : </p>
<ul class="pagination">
    <?php 
    if(!empty($histori_rm))
    foreach($histori_rm as $val): ?>
        <?php 
        $time = strtotime($val['tanggal_periksa']);
        $myFormatForView = date("d F Y", $time);
        if(isset($val->rekamMedis[0]->rm_id) && $val->rekamMedis[0]->rm_id==$model->rm_id)
        ?>
        <li <?= $val->rekamMedis[0]->rm_id==$model->rm_id ? 'class="active"' : "" ?>>
            <a target="_blank" href="<?= Url::to(['rekam-medis/view','id'=>utf8_encode(Yii::$app->security->encryptByKey( $val->rekamMedis[0]->rm_id, Yii::$app->params['kunciInggris'] ))]) ?>"> <?= $myFormatForView ?> </a>
        </li>
    <?php endforeach; ?>
</ul>

<?php if ($kunjungan->tipe_kunjungan=='Rawat Inap'): 
$histori_rm_inap = RekamMedis::find()->where(['kunjungan_id'=>$kunjungan->kunjungan_id])->orderBy(['tgl_periksa'=>SORT_ASC])->all();
?>
<p>Histori Rawat Inap : </p>
<ul class="pagination">
    <?php 
    $alreadyDiagToday = 0;
    if(!empty($histori_rm_inap))
    foreach($histori_rm_inap as $val): ?>
        <?php 
        $time = strtotime($val['tgl_periksa']);
        $myFormatForView = date("d F Y", $time);
        if ($myFormatForView == date("d F Y"))
            $alreadyDiagToday = 1;
        if($val['rm_id']==$model->rm_id)
        ?>
        <li <?= $val['rm_id']==$model->rm_id ? 'class="active"' : "" ?>>
            <a href="<?= Url::to(['rekam-medis/update','id'=>utf8_encode(Yii::$app->security->encryptByKey( $val['rm_id'], Yii::$app->params['kunciInggris'] ))]) ?>"> <?= $myFormatForView ?> </a>
        </li>
    <?php endforeach; ?>
</ul>
<?php if($alreadyDiagToday==0 && (!$model->isNewRecord)){
    echo '<br/>';
    echo Html::a('<i class="fa fa-plus"></i> Rekam Medis Hari Ini',['rekam-medis/create','kunjungan_id'=>utf8_encode(Yii::$app->security->encryptByKey( $kunjungan->kunjungan_id, Yii::$app->params['kunciInggris'] ))],['class'=>'btn green-jungle btn-outline sbold uppercase']);
    }?>
<?php endIf; ?>