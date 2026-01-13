<?php

namespace app\models;
use app\models\EklaimIcd9cm;
use app\models\bridging\Eklaim;

use Yii;

/**
 * This is the model class for table "kunjungan_eklaim".
 *
 * @property integer $kunjungan_id
 * @property string $nomor_sep
 * @property integer $kelas_rawat
 * @property integer $adl_sub_acute
 * @property integer $adl_chronic
 * @property integer $icu_indikator
 * @property integer $icu_los
 * @property integer $ventilator_hour
 * @property integer $upgrade_class_ind
 * @property integer $upgrade_class_class
 * @property integer $upgrade_class_los
 * @property string $add_payment_pct
 * @property double $birth_weight
 * @property integer $discharge_status
 * @property string $procedure
 * @property string $tarif_rs
 * @property string $tarif_poli_eks
 * @property string $kode_tarif
 * @property string $payor_id
 * @property string $payor_cd
 * @property string $cob_cd
 */
class KunjunganEklaim extends /*\app\models\bridging\Eklaim//*/\yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'kunjungan_eklaim';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kunjungan_id'], 'required','on'=>'init'],
            [['kunjungan_id','nomor_sep','kelas_rawat','add_payment_pct','birth_weight','payor_id','cob_cd'], 'required','on'=>'pendaftaran'],
            [['adl_sub_acute','adl_chronic','icu_indikator','icu_los', 'ventilator_hour', 'discharge_status','procedure'], 'required','on'=>'pemeriksaan'],
            [['kunjungan_id', 'kelas_rawat', 'adl_sub_acute', 'adl_chronic', 'icu_indikator', 'icu_los', 'ventilator_hour', 'upgrade_class_ind', 'upgrade_class_los', 'discharge_status'], 'integer'],
            [['birth_weight', 'tarif_rs', 'tarif_poli_eks'], 'number'],
            [['upgrade_class_class'], 'string'],
            [['nomor_sep'], 'string', 'max' => 50],
            [['add_payment_pct', 'kode_tarif', 'payor_cd', 'cob_cd'], 'string', 'max' => 255],
            [['payor_id'], 'string', 'max' => 11],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'kunjungan_id' => 'Kunjungan ID',
            'nomor_sep' => 'Nomor Sep',
            'kelas_rawat' => 'Kelas Rawat',
            'adl_sub_acute' => 'Adl Sub Acute',
            'adl_chronic' => 'Adl Chronic',
            'icu_indikator' => 'Icu Indikator',
            'icu_los' => 'Icu Los',
            'ventilator_hour' => 'Ventilator Hour',
            'upgrade_class_ind' => 'Upgrade Class Ind',
            'upgrade_class_class' => 'Upgrade Class Class',
            'upgrade_class_los' => 'Upgrade Class Los',
            'add_payment_pct' => 'Add Payment Pct',
            'birth_weight' => 'Birth Weight',
            'discharge_status' => 'Discharge Status',
            'procedure' => 'Procedure',
            'tarif_rs' => 'Tarif Rs',
            'tarif_poli_eks' => 'Tarif Poli Eks',
            'kode_tarif' => 'Kode Tarif',
            'payor_id' => 'Payor ID',
            'payor_cd' => 'Payor Cd',
            'cob_cd' => 'Cob Cd',
        ];
    }

    public function getProceduresHash(){
        $p = [];
        $pos = 2;
        foreach ($this->procedures as $key => $value) {
            $p[] = substr($value, 0, $pos) . '.' . substr($value, $pos);
        }
        return implode('#', $p);
    }

    public function getDiagnosasHash(){
        $p = [];
        foreach ($this->kunjungan->rekmed->rmDiagnoses as $key => $value) {
            $p[] = $value->kode;
        }
        return implode('#', $p);
    }

    public function getKunjungan()
    {
        return $this->hasOne(Kunjungan::className(), ['kunjungan_id' => 'kunjungan_id']);
    }


    public function getProcedures()
    {
        if ($this->procedure==null || $this->procedure=='')
            return [];
        $model = KunjunganEklaim::findOne($this->kunjungan_id);
        return explode(':::', $model->procedure);
    }

    public function getProceduresText()
    {
        $p = [];
        $model = KunjunganEklaim::findOne($this->kunjungan_id);
        foreach ($model->procedures as $key => $value) 
        {
            $p[] = EklaimIcd9cm::findOne($value)->short_desc;
        }
        return $p;
    }

    public function save($runValidation = true, $attributeNames = NULL){
        if($this->scenario =='pemeriksaan'){
            if ($this->procedure!=null && $this->procedure!=''){
                $this->procedure = implode(':::', $this->procedure);
            }

            if ($this->icu_indikator==0){
                $this->icu_los = 0;
                $this->ventilator_hour = 0;
            }
        }
        return parent::save($runValidation, $attributeNames);
    }

    public function getPayor(){
        // print_r($this->payor_id);
        $eklaim = new Eklaim;
        return $eklaim->payor($this->payor_id);
    }

    public function getIsComplete(){
        if(
            $this->kunjungan_id !== null && 
            $this->nomor_sep !== null && 
            $this->kelas_rawat !== null && 
            $this->adl_sub_acute !== null && 
            $this->adl_chronic !== null && 
            $this->icu_indikator !== null && 
            $this->icu_los !== null && 
            $this->ventilator_hour !== null && 
            $this->upgrade_class_ind !== null && 
            // $this->upgrade_class_class !== null && 
            $this->upgrade_class_los !== null && 
            $this->add_payment_pct !== null && 
            $this->birth_weight !== null && 
            $this->discharge_status !== null && 
            $this->procedure !== null && 
            $this->tarif_rs !== null && 
            $this->tarif_poli_eks !== null && 
            // $this->kode_tarif !== null && 
            $this->payor_id !== null && 
            // $this->payor_cd !== null && 
            $this->cob_cd !== null
        )
            return true;

        else return false;
    }

    public function getStatusDesc(){
        if ($this->status == null && !$this->isComplete)
            return 'Data belum lengkap.';
        elseif ($this->status == null && $this->isComplete)
            return 'Data siap untuk dipush ke Eklaim.';
        elseif ($this->status=='new_claim')
            return 'Data telah berhasil terdaftar untuk diclaim.';
        elseif ($this->status=='set_claim_data')
            return 'Data telah berhasil diclaim.';
        elseif ($this->status=='grouper_1')
            return 'Data telah berhasil digrouping pada tahap 1.';
        elseif ($this->status=='grouper_2')
            return 'Data telah berhasil digrouping pada tahap 2.';
    }
}
