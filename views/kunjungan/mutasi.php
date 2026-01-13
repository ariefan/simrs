<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\UnitMedis;
use app\models\User;
use app\models\Ruang;
use app\models\Kelas;




if ($to=='ranap'){
    $opsiMutasi = [
        '1'=>'Rujuk ke Rawat Inap',
    ];
    echo '<h1>Mutasi</h1>';
}
elseif($to=='rajal'){
    $opsiMutasi = [
        '0'=>'Rujuk Ke Poli Lain',
    ];
    echo '<h1>Rujuk Internal</h1>';
}
else{
    $opsiMutasi = [
        '1'=>'Rujuk ke Rawat Inap',
        '0'=>'Rujuk Ke Poli Lain',
    ];
    echo '<h1>Mutasi</h1>';
}
?>


<style type="text/css">
.select2-dropdown {
  z-index: 99999;
}
</style>
<?php $form = ActiveForm::begin(["id"=>"form-mutasi"]); ?>
    <div class="row">
        <div class="col-md-6">
            <?= ($to!='all')? "<div style='display:none'>":"" ?>
                <?= $form->field($model, 'toRanap')->dropDownList($opsiMutasi,[
                        'class'=>'form-control toRanap'
                    ]
                ) ?>
            <?= ($to!='all')? "</div>":"" ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'dpjp')->widget(Select2::classname(), [
              'data'=>ArrayHelper::map(User::find()->select(['user.id as user_id','nama'])->leftJoin('dokter','user.id=dokter.user_id')->where(['role'=>25])->asArray()->all(),'user_id','nama'), 
              'value'=>(Yii::$app->user->identity->role==USER::ROLE_DOKTER)? Yii::$app->user->identity->id : "",
              'options' => ['placeholder' => 'Pilih Dokter'],
          ]); ?>
        </div>
    </div>

    


    <div class="form-group ruangan">
        <div class="row">
            <div class="col-md-4">
                <?= $form->field($model, 'ruang_cd')->widget(Select2::classname(), [
                    'data' =>  ArrayHelper::map(Ruang::find()->select(['ruang_cd', 'ruang_nm'])->where(['status'=>0])->asArray()->all(), 'ruang_cd', 'ruang_nm'),
                    'options' => ['placeholder' => 'Pilih Ruang Ranap'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]); ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'kelas_cd')->widget(Select2::classname(), [
                    'data' =>  ArrayHelper::map(Kelas::find()->select(['kelas_cd', 'kelas_nm'])->asArray()->all(), 'kelas_cd', 'kelas_nm'),
                    'options' => ['placeholder' => 'Kosongkan apabila menyesuaikan dengan kelas ruang'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]); ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'rl_31')->widget(Select2::classname(), [
                    'data' =>  ArrayHelper::map(\Yii::$app->db->createCommand('SELECT * FROM rl_ref_31')->queryAll(), 'no', 'jenis_pelayanan'),
                    'options' => ['placeholder' => 'Harap diisi untuk kepentingan RL'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]); ?>
            </div>
            
        </div>
    </div>

    <div class="form-group poli" style="display: none;">
    	<?= $form->field($model, 'poliTujuan')->dropDownList(
		      ArrayHelper::map(UnitMedis::find()->all(), 'medunit_cd', 'medunit_nm')
		    ) ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Mutasi', ['class' => 'btn btn-success']) ?>
    </div>

<?php ActiveForm::end(); ?>
<script type="text/javascript">
    function switchh(){
        if($('.toRanap').val()==1)
        {
            $('.poli').hide();
            $('.ruangan').show();
        }
        else
        {
            $('.ruangan').hide();
            $('.poli').show();
        }
    }

</script>
<?php $this->registerJs("
        switchh();
	$('.toRanap').change(function(){
		switchh();
	})
") ?>