<?php
use app\models\KunjunganEklaim;

namespace app\models;

use Yii;

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
class Kunjungan extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $pasien_nama;

    //attr Mutasi
    public $toRanap, $poliTujuan;
    
    public static function tableName()
    {
        return 'kunjungan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ruang_cd','rl_31','dpjp'],'required','on'=>'createRawatInap'],
            [['medunit_cd','dpjp'],'required','on'=>'createRawatJalan'],
            [['medunit_cd','dpjp'],'required','on'=>'create'],
            ['ruang_cd','required','when'=>function($model){
                return (($model->toRanap==1));
            }, 
            'whenClient' => "function (attribute, value) {
                    return $('#kunjungan-toranap').val() == '1';
                }"],
            [['toRanap','poliTujuan'],'required', 'on'=>'mutasi'],
            [['toRanap','klinik_id', 'user_id','dokter_periksa','jns_kunjungan_id','cara_id','asal_id','rl_31','dpjp'], 'integer'],
            [['tanggal_periksa','status_rm', 'jam_masuk', 'jam_selesai', 'created','pasien_nama','tipe_kunjungan','baru_lama','rm_ketemu','rm_dikirim','rm_kembali','jenis_keluar'], 'safe'],
            [['status','poliTujuan','ruang_cd','kelas_cd','ruang_cd','insurance_cd','catatan_keluar'], 'string'],
            [['medunit_cd'], 'string', 'max' => 20], 
            [['mr'], 'string', 'max' => 25],
            [['user_input'], 'string', 'max' => 100],
            [['medunit_cd'], 'exist', 'skipOnError' => true, 'targetClass' => UnitMedis::className(), 'targetAttribute' => ['medunit_cd' => 'medunit_cd']], 
            [['jns_kunjungan_id'], 'exist', 'skipOnError' => true, 'targetClass' => JenisKunjungan::className(), 'targetAttribute' => ['jns_kunjungan_id' => 'jns_kunjungan_id']], 
            [['klinik_id'], 'exist', 'skipOnError' => true, 'targetClass' => Klinik::className(), 'targetAttribute' => ['klinik_id' => 'klinik_id']],
            [['mr'], 'exist', 'skipOnError' => true, 'targetClass' => Pasien::className(), 'targetAttribute' => ['mr' => 'mr']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'kunjungan_id' => 'Kunjungan ID',
            'baru_lama' => 'Jenis',
            'jns_kunjungan_id' => 'Jenis Kunjungan',
            'pasien_nama' => 'Nama Pasien',
            'klinik_id' => 'Klinik ID',
            'mr' => 'No Rekam Medis',
            'jk'=>'Jenis Kelamin',
            'tanggal_periksa' => 'Tanggal Masuk',
            'jam_masuk' => 'Waktu Masuk',
            'jam_selesai' => 'Waktu Selesai',
            'status' => 'Status',
            'status_rm' => 'Status RM',
            'rm_ketemu' => 'Ketemu',
            'rm_dikirim' => 'Dikirim',
            'rm_kembali' => 'Kembali',
            'created' => 'Created',
            'user_input' => 'User Input',
            'user_id' => 'User ID',
            'dokter_periksa' => 'Dokter',
            'medunit_cd' => 'Unit / Poli',
            'asal_id' => 'Asal Pasien',
            'cara_id' => 'Cara Bayar',
            'cara.cara_nama' => 'Cara Bayar',
            'ruang_cd' => 'Ruangan ',
            'kelas_cd' => 'Kelas Rawat Inap',
            'rl_31' => 'Jenis Pelayanan',
            'ruang_nm' => 'Ruangan',
            'dpjp' => "Dokter Penanggung Jawab",
            

        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    
    public function getCara()
    {
        return $this->hasOne(CaraBayar::className(), ['cara_id' => 'cara_id']);
    }

    public function getAsal()
    {
        return $this->hasOne(AsalPasien::className(), ['asal_id' => 'asal_id']);
    }


    public function getKlinik()
    {
        return $this->hasOne(Klinik::className(), ['klinik_id' => 'klinik_id']);
    }

    public function getUnit()
    {
        return $this->hasOne(UnitMedis::className(), ['medunit_cd' => 'medunit_cd']);
    }

    public function getJenis()
    {
        return $this->hasOne(JenisKunjungan::className(), ['jns_kunjungan_id' => 'jns_kunjungan_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMr0()
    {
        return $this->hasOne(Pasien::className(), ['mr' => 'mr']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getDokter()
    {
        return $this->hasOne(Dokter::className(), ['user_id' => 'dpjp']);
    }

    public function getRuang0()
    {
        return $this->hasOne(Ruang::className(), ['ruang_cd' => 'ruang_cd']);
    }
    

    /**
     * @return \yii\db\ActiveQuery
     */

    public function getRekamMedis()
    {
        return $this->hasMany(RekamMedis::className(), ['kunjungan_id' => 'kunjungan_id']);
    }
    
    public function getRekmed()
    {
        return $this->hasOne(RekamMedis::className(), ['kunjungan_id' => 'kunjungan_id']);
    }

    /** 
    * @return \yii\db\ActiveQuery 
    */ 
   public function getBayar() 
   { 
       return $this->hasMany(Bayar::className(), ['kunjungan_id' => 'kunjungan_id']); 
   }   
   public function getBayar0() 
   { 
       return $this->hasOne(Bayar::className(), ['kunjungan_id' => 'kunjungan_id']); 
   }

   public function isBaru($mr){
        $d = Kunjungan::find()->where(['mr'=>$mr])->asArray()->all();
        if(empty($d)) return true;

        return false;
   }

   public function getCanMutasi()
   {
        $child = Kunjungan::find()->where(['parent_id'=>$this->kunjungan_id])->count();
        return ($child == 0); 
   }

   public function getSurveilans($start_date,$end_date,$tipe_kunjungan,$jenis_diagnosa)
   {
        if($jenis_diagnosa=="primer"){
            $sql_jns_diagnosa = "LEFT JOIN rm_diagnosis AS d
                    ON diagnosis.`kode` = d.`kode` ";
        } elseif($jenis_diagnosa=="sekunder"){
            $sql_jns_diagnosa = "LEFT JOIN rm_diagnosis_banding AS d
                    ON diagnosis.`kode` = d.`kode` ";
        } else {
            $sql_jns_diagnosa = "
                    LEFT JOIN rm_diagnosis AS d
                        ON diagnosis.`kode` = d.`kode` 
                    LEFT JOIN rm_diagnosis_banding AS e
                        ON diagnosis.`kode` = e.`kode` ";
        }

        $sql = "SELECT 
                  diagnosis.kode,
                  diagnosis.nama,
                  SUM(
                    CASE
                      WHEN pasien.`jk` = 'Laki-Laki' 
                      AND DATEDIFF(
                        pasien.`tanggal_lahir`,
                        kunjungan.`tanggal_periksa`
                      ) BETWEEN 0 
                      AND 6 
                      THEN 1 
                      ELSE 0 
                    END
                  ) AS K_5,
                  SUM(
                    CASE
                      WHEN pasien.`jk` = 'Perempuan' 
                      AND DATEDIFF(
                        pasien.`tanggal_lahir`,
                        kunjungan.`tanggal_periksa`
                      ) BETWEEN 0 
                      AND 6 
                      THEN 1 
                      ELSE 0 
                    END
                  ) AS K_6,
                  SUM(
                    CASE
                      WHEN pasien.`jk` = 'Laki-Laki' 
                      AND DATEDIFF(
                        pasien.`tanggal_lahir`,
                        kunjungan.`tanggal_periksa`
                      ) BETWEEN 6 
                      AND 28 
                      THEN 1 
                      ELSE 0 
                    END
                  ) AS K_7,
                  SUM(
                    CASE
                      WHEN pasien.`jk` = 'Perempuan' 
                      AND DATEDIFF(
                        pasien.`tanggal_lahir`,
                        kunjungan.`tanggal_periksa`
                      ) BETWEEN 6 
                      AND 28 
                      THEN 1 
                      ELSE 0 
                    END
                  ) AS K_8,
                  SUM(
                    CASE
                      WHEN pasien.`jk` = 'Laki-Laki' 
                      AND DATEDIFF(
                        pasien.`tanggal_lahir`,
                        kunjungan.`tanggal_periksa`
                      ) BETWEEN 28 
                      AND 365 
                      THEN 1 
                      ELSE 0 
                    END
                  ) AS K_9,
                  SUM(
                    CASE
                      WHEN pasien.`jk` = 'Perempuan' 
                      AND DATEDIFF(
                        pasien.`tanggal_lahir`,
                        kunjungan.`tanggal_periksa`
                      ) BETWEEN 28 
                      AND 365 
                      THEN 1 
                      ELSE 0 
                    END
                  ) AS K_10,
                  SUM(
                    CASE
                      WHEN pasien.`jk` = 'Laki-Laki' 
                      AND TIMESTAMPDIFF(
                        YEAR,
                        pasien.`tanggal_lahir`,
                        kunjungan.`tanggal_periksa`
                      ) BETWEEN 1 
                      AND 4 
                      THEN 1 
                      ELSE 0 
                    END
                  ) AS K_11,
                  SUM(
                    CASE
                      WHEN pasien.`jk` = 'Perempuan' 
                      AND TIMESTAMPDIFF(
                        YEAR,
                        pasien.`tanggal_lahir`,
                        kunjungan.`tanggal_periksa`
                      ) BETWEEN 1 
                      AND 4 
                      THEN 1 
                      ELSE 0 
                    END
                  ) AS K_12,
                  SUM(
                    CASE
                      WHEN pasien.`jk` = 'Laki-Laki' 
                      AND TIMESTAMPDIFF(
                        YEAR,
                        pasien.`tanggal_lahir`,
                        kunjungan.`tanggal_periksa`
                      ) BETWEEN 4 
                      AND 14 
                      THEN 1 
                      ELSE 0 
                    END
                  ) AS K_13,
                  SUM(
                    CASE
                      WHEN pasien.`jk` = 'Perempuan' 
                      AND TIMESTAMPDIFF(
                        YEAR,
                        pasien.`tanggal_lahir`,
                        kunjungan.`tanggal_periksa`
                      ) BETWEEN 4 
                      AND 14 
                      THEN 1 
                      ELSE 0 
                    END
                  ) AS K_14,
                  SUM(
                    CASE
                      WHEN pasien.`jk` = 'Laki-Laki' 
                      AND TIMESTAMPDIFF(
                        YEAR,
                        pasien.`tanggal_lahir`,
                        kunjungan.`tanggal_periksa`
                      ) BETWEEN 14 
                      AND 24 
                      THEN 1 
                      ELSE 0 
                    END
                  ) AS K_15,
                  SUM(
                    CASE
                      WHEN pasien.`jk` = 'Perempuan' 
                      AND TIMESTAMPDIFF(
                        YEAR,
                        pasien.`tanggal_lahir`,
                        kunjungan.`tanggal_periksa`
                      ) BETWEEN 14 
                      AND 24 
                      THEN 1 
                      ELSE 0 
                    END
                  ) AS K_16,
                  SUM(
                    CASE
                      WHEN pasien.`jk` = 'Laki-Laki' 
                      AND TIMESTAMPDIFF(
                        YEAR,
                        pasien.`tanggal_lahir`,
                        kunjungan.`tanggal_periksa`
                      ) BETWEEN 24 
                      AND 44 
                      THEN 1 
                      ELSE 0 
                    END
                  ) AS K_17,
                  SUM(
                    CASE
                      WHEN pasien.`jk` = 'Perempuan' 
                      AND TIMESTAMPDIFF(
                        YEAR,
                        pasien.`tanggal_lahir`,
                        kunjungan.`tanggal_periksa`
                      ) BETWEEN 24 
                      AND 44 
                      THEN 1 
                      ELSE 0 
                    END
                  ) AS K_18,
                  SUM(
                    CASE
                      WHEN pasien.`jk` = 'Laki-Laki' 
                      AND TIMESTAMPDIFF(
                        YEAR,
                        pasien.`tanggal_lahir`,
                        kunjungan.`tanggal_periksa`
                      ) BETWEEN 44 
                      AND 64 
                      THEN 1 
                      ELSE 0 
                    END
                  ) AS K_19,
                  SUM(
                    CASE
                      WHEN pasien.`jk` = 'Perempuan' 
                      AND TIMESTAMPDIFF(
                        YEAR,
                        pasien.`tanggal_lahir`,
                        kunjungan.`tanggal_periksa`
                      ) BETWEEN 44 
                      AND 64 
                      THEN 1 
                      ELSE 0 
                    END
                  ) AS K_20,
                  SUM(
                    CASE
                      WHEN pasien.`jk` = 'Laki-Laki' 
                      AND TIMESTAMPDIFF(
                        YEAR,
                        pasien.`tanggal_lahir`,
                        kunjungan.`tanggal_periksa`
                      ) >= 64 
                      THEN 1 
                      ELSE 0 
                    END
                  ) AS K_21,
                  SUM(
                    CASE
                      WHEN pasien.`jk` = 'Perempuan' 
                      AND TIMESTAMPDIFF(
                        YEAR,
                        pasien.`tanggal_lahir`,
                        kunjungan.`tanggal_periksa`
                      ) >= 64 
                      THEN 1 
                      ELSE 0 
                    END
                  ) AS K_22,
                  SUM(
                    CASE
                      WHEN pasien.`jk` = 'Laki-Laki' 
                      THEN 1 
                      ELSE 0 
                    END
                  ) AS K_23,
                  SUM(
                    CASE
                      WHEN pasien.`jk` = 'Perempuan' 
                      THEN 1 
                      ELSE 0 
                    END
                  ) AS K_24,
                  SUM(
                    CASE
                      WHEN pasien.`jk` = 'Laki-Laki' 
                      THEN 1 
                      ELSE 0 
                    END
                  ) +
                  SUM(
                    CASE
                      WHEN pasien.`jk` = 'Perempuan' 
                      THEN 1 
                      ELSE 0 
                    END
                  ) AS K_25,
                  SUM(
                    CASE
                      WHEN kunjungan.jenis_keluar IN (2,3) 
                      THEN 1 
                      ELSE 0 
                    END
                  ) AS K_26 
                FROM
                  diagnosis 
                  $sql_jns_diagnosa
                  LEFT JOIN rekam_medis 
                    ON rekam_medis.`rm_id` = d.`rm_id` 
                  LEFT JOIN pasien 
                    ON rekam_medis.mr = pasien.`mr` 
                  LEFT JOIN kunjungan 
                    ON kunjungan.`kunjungan_id` = rekam_medis.`kunjungan_id` 
                    AND kunjungan.`tanggal_periksa` BETWEEN '$start_date' 
                      AND '$end_date' 
                      AND kunjungan.tipe_kunjungan = '$tipe_kunjungan' 
                GROUP BY diagnosis.`kode` ";

        $connection = Yii::$app->db;
        $command = $connection->createCommand($sql);     
        return $command->queryAll();

   }

    public function getEklaim()
    {
        $c = KunjunganEklaim::find()->where(['kunjungan_id'=>$this->kunjungan_id])->count();
        if ($c>0)
            return $this->hasOne(KunjunganEklaim::className(), ['kunjungan_id' => 'kunjungan_id']);
        else
            return false;
    }

   public function mutasiTo($id,$dpjp)
   {
        $parentModel = Kunjungan::findOne($id);
        $childModel  = new Kunjungan();
        $childModel->attributes = $parentModel->attributes;
        $childModel->dpjp = $dpjp;
        
        if ($this->toRanap){
            $childModel->tipe_kunjungan = 'Rawat Inap';
            $childModel->medunit_cd = null;
            $childModel->rl_31 = $this->rl_31;
            $childModel->ruang_cd = $this->ruang_cd;
            if ($this->kelas_cd=='')
                $childModel->kelas_cd = $this->ruang0->kelas_cd;
            else
                $childModel->kelas_cd = $this->kelas_cd;
        }
        else{
            $childModel->tipe_kunjungan = 'Rawat Jalan';
            $childModel->medunit_cd = $this->poliTujuan;
            $childModel->rl_31 = null;
        }

        
        $childModel->status_rm = 'Datang';
        $childModel->tanggal_periksa = date('Y-m-d');
        $childModel->jam_masuk = date('Y-m-d H:i:s');
        $childModel->created = date('Y-m-d H:i:s');
        $childModel->status = 'antri';
        $childModel->user_input = Yii::$app->user->identity->username;
        $childModel->user_id = Yii::$app->user->identity->id;
        $childModel->parent_id = $id;
        if($this->toRanap){
            $ruang = Ruang::findOne($this->ruang_cd);
            if(@$ruang->status=='0')
            {
                    $ruang->status = '1';
                    $ruang->save();
                    $childModel->save();
                    return $childModel->medunit_cd;
            }    
            else{
                return false;
            }
        }else{
            $childModel->save();
            return $childModel->medunit_cd;
        }    

   }

    public function save($runValidation = true, $attributeNames = NULL){
        $new = $this->isNewRecord;
        $model = parent::save($runValidation, $attributeNames);
        if($this->asal_id!=1 && strtoupper($this->mr0->jenis_asuransi)=='BPJS' && $this->mr0->no_asuransi!=''&& $this->mr0->no_identitas!='' && $new){
            $eklaim = new KunjunganEklaim();
            $eklaim->scenario = 'init';
            $eklaim->kunjungan_id = $this->kunjungan_id;
            $eklaim->save();
        }
        return $model;
    }
   
}
