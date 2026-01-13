<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "rm_rad".
 *
 * @property string $id
 * @property string $rm_id
 * @property string $medicalunit_cd
 * @property string $nama
 * @property string $catatan
 * @property string $hasil
 * @property integer $dokter
 * @property string $dokter_nama
 *
 * @property RekamMedis $rm
 * @property UnitMedisItem $medicalunitCd
 */
class RmRad extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rm_rad';'unit_medis_item'; 'Pasien';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dokter'],'required'],
            [['rm_id', 'dokter'], 'integer'],
            [['catatan', 'hasil'], 'string'],
            [['medicalunit_cd'], 'string', 'max' => 20],
            [['nama'], 'string', 'max' => 255],
            [['dokter_nama'], 'string', 'max' => 50],
            [['rm_id'], 'exist', 'skipOnError' => true, 'targetClass' => RekamMedis::className(), 'targetAttribute' => ['rm_id' => 'rm_id']],
            [['medicalunit_cd'], 'exist', 'skipOnError' => true, 'targetClass' => UnitMedisItem::className(), 'targetAttribute' => ['medicalunit_cd' => 'medicalunit_cd']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'rm_id' => 'Rm ID',
            'medicalunit_cd' => 'Medicalunit Cd',
            'nama' => 'Nama',
            'catatan' => 'Catatan',
            'hasil' => 'Hasil',
            'dokter' => 'Dokter',
            'dokter_nama' => 'Dokter Nama',
        ];
    }

    public function getDataRad($tanggal){
        $sql = "SELECT DISTINCT 
                  kunjungan_id,
                  rm_id,
                  pasien.mr,
                  pasien.`nama`,
                  kunjungan.`tanggal_periksa`,
                  unit_medis.`medunit_nm` AS poli_pengirimi,
                  IFNULL(dokter.`nama`,'Langsung') AS dokter_pengirim,
                  GROUP_CONCAT(
                    unit_medis_item.`medicalunit_nm`
                  ) AS pemeriksaan ,
                  GROUP_CONCAT(rm_rad.catatan) AS catatan
                FROM
                  kunjungan 
                  LEFT JOIN rekam_medis USING (kunjungan_id) 
                  LEFT JOIN pasien 
                    ON pasien.mr = kunjungan.`mr` 
                  LEFT JOIN dokter 
                    ON dokter.`user_id` = rekam_medis.`user_id` 
                  LEFT JOIN unit_medis ON unit_medis.medunit_cd = kunjungan.medunit_cd 
                  LEFT JOIN rm_rad USING (rm_id) 
                  LEFT JOIN unit_medis_item 
                    ON rm_rad.`medicalunit_cd` = unit_medis_item.`medicalunit_cd` 
                WHERE tanggal_periksa = '$tanggal'
                GROUP BY kunjungan_id ";

        $connection = Yii::$app->db;
        $model = $connection->createCommand($sql);
        return $model->queryAll();
    }

    public function getNamaPasien($id){
        $SQL = "SELECT Distinct a.nama as nama from pasien a, rekam_medis b, rm_rad c where (b.rm_id=c.rm_id) and (c.rm_id=$id) and (a.mr=b.mr) group by a.nama";
        $daftar = Pasien::findBySql($SQL)->asArray()->all();
        if($daftar[0]['nama'] != null){
            $hasil =  $daftar[0]['nama'];
        }
        
        return $hasil;
    }

    public function getNamaLab($id){
        $SQL = "SELECT Distinct a.medunit_nm as nama from unit_medis a, rm_rad b, rekam_medis c, kunjungan d where (a.medunit_cd=d.medunit_cd) and (b.rm_id=$id) and (d.kunjungan_id=c.kunjungan_id) and (b.rm_id=c.rm_id) group by a.medunit_nm";
        $daftar = UnitMedisItem::findBySql($SQL)->asArray()->all();
        if($daftar[0]['nama'] != null){
            $hasil =  $daftar[0]['nama'];
        }
        
        return $hasil;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRm()
    {
        return $this->hasOne(RekamMedis::className(), ['rm_id' => 'rm_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJenis()
    {
        return $this->hasOne(UnitMedisItem::className(), ['medicalunit_cd' => 'medicalunit_cd']);
    }
}
