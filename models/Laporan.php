<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "kunjungan".
 *
 * @property string $kunjungan_id
 * @property integer $klinik_id
 * @property string $mr
 * @property string $tanggal_periksa
 * @property string $jam_masuk
 * @property string $jam_selesai
 * @property string $status
 * @property string $created
 * @property string $user_input
 * @property integer $user_id
 *
 * @property Klinik $klinik
 * @property Pasien $mr0
 * @property User $user
 * @property RekamMedis[] $rekamMedis
 */
class Laporan extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $tahun;

    public function rules()
    {
        return [
            [['tahun'], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'tahun' => 'tahun',
        ];
    }
    
    public function setTahun($thn){
        $this->tahun = $thn;
    }

    public function getTahun(){
        return $this->tahun;
    }

    public function getTahunDefault(){
        $SQL = "select distinct max(year(tanggal_periksa)) from kunjungan";
        $x=Laporan::findBySql($SQL)->asArray()->one();
        $tahun=implode($x);
        return $tahun;
    }
}
