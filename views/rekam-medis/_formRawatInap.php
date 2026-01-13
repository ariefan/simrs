<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\bootstrap\Modal;
use app\models\Pasien;
use app\models\Obat;
use app\models\Tindakan;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\web\JsExpression;

?>

<?php if(!isset($readOnly)) $readOnly = false; ?>

<?php
    Modal::begin([
            'id' => 'modalInap',
        ]);

    echo "<div id='modalInapContent'></div>";

    Modal::end();

?>

<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption font-red-sunglo">
                    <i class="icon-settings font-red-sunglo"></i>
                    <span class="caption-subject bold uppercase">RAWAT INAP</span>
                </div>
            </div>
            
            <br/>
            <div class="portlet-body form">
                <ul class="nav nav-pills">
                        <li class="active">
                            <a href="#tab_80" data-toggle="tab"> RM HARIAN </a>
                        </li>
                        <li>
                            <a href="#tab_90" data-toggle="tab"> GIZI </a>
                        </li>
                </ul>
                <div class="tab-content">
                    
                    <div class="tab-pane fade active in" id="tab_80">
                        <table class="table table-striped">
                           <thead>
                               <th>Tgl.</th>
                               <th>Anamnesis (Subyekif)</th>
                               <th>Pemeriksaan Fisik (Obyektif)</th>
                               <th>Assesment</th>
                               <th>Plan</th>
                               <?php if(!$readOnly){ ?>
                                   <th width="15%">Action</th>
                                <?php } ?>
                           </thead>
                           <?php foreach ($model->rmInap as $key => $value): ?>
                                <tr>
                                    <td><?= date("d/m/Y", strtotime($value->created))?></td>
                                    <td><?= $value->anamnesis?></td>
                                    <td><?= $value->pemeriksaan_fisik?></td>
                                    <td><?= $value->assesment?></td>
                                    <td><?= $value->plan?></td>
                                    <?php if(!$readOnly){ ?>
                                        <td>
                                            <?= Html::button('<i class="fa fa-pencil"></i>', ['value'=>Url::toRoute(['rm-inap/update','id'=>$value->id, ]),'class' => 'btn blue-soft btn-outline sbold uppercase modalInapWindow']) ?>
                                            <?= Html::a('<i class="fa fa-trash-o"></i>', Url::to(['rm-inap/delete','id'=>$value->id]), [
                                                'title' => Yii::t('yii', 'Hapus'),
                                                'class'=> 'btn red btn-outline sbold uppercase',
                                                'data-confirm' => Yii::t('yii', 'Apakah Anda Yakin akan Menghapus?'),
                                                'data-method' => 'post',
                                            ]); ?>
                                        </td>
                                        <?php } ?>
                                </tr>
                            <?php endForeach ?>
                       </table>
                       <?php if(!$readOnly){ ?>
                           <div class="form-group">
                                <?= Html::button('<i class="fa fa-plus"></i> SOAP', ['value'=>Url::toRoute(['rm-inap/create','rm_id'=>$model->rm_id]),'class' => 'btn green-jungle btn-outline sbold uppercase modalInapWindow']) ?>
                            </div>
                        <?php } ?>
                    </div>

                    <div class="tab-pane fade in" id="tab_90">
                       <table class="table table-striped">
                           <thead>
                                <th>Tgl.</th>
                               <th width="80%">Gizi</th>
                               <?php if(!$readOnly){ ?>
                                   <th width="15%">Action</th>
                                <?php } ?>
                           </thead>
                           <?php foreach ($model->rmInapGizi as $key => $value): ?>
                                <tr>
                                    <td><?= date("d/m/Y", strtotime($value->created))?></td>
                                    <td><?= $value->kode_diet?></td>
                                    <?php if(!$readOnly){ ?>
                                        <td>
                                            <?= Html::button('<i class="fa fa-pencil"></i>', ['value'=>Url::toRoute(['rm-inap-gizi/update','id'=>$value->id, ]),'class' => 'btn blue-soft btn-outline sbold uppercase modalInapWindow']) ?>
                                            <?= Html::a('<i class="fa fa-trash-o"></i>', Url::to(['rm-inap-gizi/delete','id'=>$value->id]), [
                                                'title' => Yii::t('yii', 'Hapus'),
                                                'class'=> 'btn red btn-outline sbold uppercase',
                                                'data-confirm' => Yii::t('yii', 'Apakah Anda Yakin akan Menghapus?'),
                                                'data-method' => 'post',
                                            ]); ?>
                                        </td>
                                    <?php } ?>
                                </tr>
                            <?php endForeach ?>
                       </table>
                       <?php if(!$readOnly){ ?>
                            <div class="form-group">
                            <?= Html::button('<i class="fa fa-plus"></i> Gizi', ['value'=>Url::toRoute(['rm-inap-gizi/create','rm_id'=>$model->rm_id]),'class' => 'btn green-jungle btn-outline sbold uppercase modalInapWindow']) ?>
                            </div>
                        <?php } ?>
                        </div>
            </div>
        </div>
    </div>
    
</div>



<?php
$script = <<< JS
    $(function(){
        $('.modalInapWindow').click(function(){
            $('#modalInap').modal('show')
                .find('#modalInapContent')
                .load($(this).attr('value'))
        })

        
    });

JS;

$this->registerJs($script);
