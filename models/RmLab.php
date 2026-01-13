<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "rm_lab".
 *
 * @property string $id
 * @property string $rm_id
 * @property string $medicalunit_cd
 * @property string $catatan
 * @property string $hasil
 * @property integer $dokter
 * @property string $dokter_nama
 *
 * @property RekamMedis $rm
 * @property UnitMedisItem $medicalunitCd
 */
class RmLab extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rm_lab'; 'unit_medis_item'; 'Pasien';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dokter'],'required'],
            [['rm_id', 'dokter'], 'integer'],
            [['catatan', 'hasil','nama'], 'string'],
            [['medicalunit_cd'], 'string', 'max' => 20],
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
            'rm_id' => 'No Rekam Medis',
            'medicalunit_cd' => 'Kode Laboratorium',
            'nama'=>'Nama Fasilitas',
            'catatan' => 'Catatan',
            'hasil' => 'Hasil',
            'dokter' => 'Dokter',
            'dokter_nama' => 'Dokter Nama',
        ];
    }

    public function getDataLab($tanggal){
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
                  GROUP_CONCAT(rm_lab.catatan) AS catatan
                FROM
                  kunjungan 
                  LEFT JOIN rekam_medis USING (kunjungan_id) 
                  LEFT JOIN pasien 
                    ON pasien.mr = kunjungan.`mr` 
                  LEFT JOIN dokter 
                    ON dokter.`user_id` = rekam_medis.`user_id` 
                  LEFT JOIN unit_medis ON unit_medis.medunit_cd = kunjungan.medunit_cd 
                  LEFT JOIN rm_lab USING (rm_id) 
                  LEFT JOIN unit_medis_item 
                    ON rm_lab.`medicalunit_cd` = unit_medis_item.`medicalunit_cd` 
                WHERE tanggal_periksa = '$tanggal'
                GROUP BY kunjungan_id ";

        $connection = Yii::$app->db;
        $model = $connection->createCommand($sql);
        return $model->queryAll();
    }

    public function getNamaPasien($id){
        $SQL = "SELECT Distinct a.nama as nama from pasien a, rekam_medis b, rm_lab c where (b.rm_id=c.rm_id) and (c.rm_id=$id) and (a.mr=b.mr) group by a.nama";
        $daftar = Pasien::findBySql($SQL)->asArray()->all();
        if($daftar[0]['nama'] != null){
            $hasil =  $daftar[0]['nama'];
        }
        
        return $hasil;
    }

    public function getNamaLab($id){
        $SQL = "SELECT Distinct a.medunit_nm as nama from unit_medis a, rm_lab b, rekam_medis c, kunjungan d where (a.medunit_cd=d.medunit_cd) and (b.rm_id=$id) and (d.kunjungan_id=c.kunjungan_id) and (b.rm_id=c.rm_id) group by a.medunit_nm";
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

    public function getAge($birthDate)
    {
        $birthDate = explode("-", $birthDate);
        $age = (date("md", date("U", mktime(0, 0, 0, $birthDate[1], $birthDate[2], $birthDate[0]))) > date("md")
          ? ((date("Y") - $birthDate[0]) - 1)
          : (date("Y") - $birthDate[0]));
        return $age;
    }
    
}
