<?php

namespace app\models;

use Yii;
use app\models\Klinik;

/**
 * This is the model class for table "rekam_medis".
 *
 * @property string $rm_id
 * @property integer $user_id
 * @property string $kunjungan_id
 * @property string $mr
 * @property string $tekanan_darah
 * @property integer $nadi
 * @property double $respirasi_rate
 * @property double $suhu
 * @property integer $berat_badan
 * @property integer $tinggi_badan
 * @property double $bmi
 * @property string $keluhan_utama
 * @property string $anamnesis
 * @property string $pemeriksaan_fisik
 * @property string $hasil_penunjang
 * @property string $deskripsi_tindakan
 * @property string $saran_pemeriksaan
 * @property string $alergi_obat
 * @property string $created
 * @property string $modified
 *
 * @property Pasien $mr0
 * @property User $user
 * @property Kunjungan $kunjungan
 * @property RmDiagnosis[] $rmDiagnoses
 * @property RmDiagnosisBanding[] $rmDiagnosisBandings
 * @property RmObat[] $rmObats
 * @property RmObatRacik[] $rmObatRaciks
 * @property RmTindakan[] $rmTindakans
 */
class RekamMedis extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $pasien_nama,$tanggal_periksa;
    public static function tableName()
    {
        return 'rekam_medis';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mr','user_id', 'kunjungan_id'],'required'],
            [['user_id', 'kunjungan_id', 'nadi', 'berat_badan', 'tinggi_badan','rl_32','rl_33'], 'integer'],
            [['respirasi_rate', 'suhu', 'bmi'], 'number'],
            [['keluhan_utama', 'anamnesis', 'pemeriksaan_fisik', 'hasil_penunjang', 'deskripsi_tindakan', 'saran_pemeriksaan','assesment','plan','rl_35_1','rl_35_2','rl_35_3','rl_34'], 'string'],
            [['created', 'modified','pasien_nama','tanggal_periksa'], 'safe'],
            [['mr'], 'string', 'max' => 25],
            [['tekanan_darah'], 'string', 'max' => 50],
            [['alergi_obat'], 'string', 'max' => 255],
            [['mr'], 'exist', 'skipOnError' => true, 'targetClass' => Pasien::className(), 'targetAttribute' => ['mr' => 'mr']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['kunjungan_id'], 'exist', 'skipOnError' => true, 'targetClass' => Kunjungan::className(), 'targetAttribute' => ['kunjungan_id' => 'kunjungan_id']],
            [['locked'],'boolean']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'rm_id' => 'Rm ID',
            'pasien_nama' => 'Nama Pasien',
            'user_id' => 'User ID',
            'kunjungan_id' => 'Kunjungan ID',
            'mr' => 'No Rekam Medis',
            'tekanan_darah' => 'TD (mmHg)',
            'nadi' => 'N (x/min)',
            'respirasi_rate' => 'RR (x/min)',
            'suhu' => 'S (C)',
            'berat_badan' => 'BB (kg)',
            'tinggi_badan' => 'TB (cm)',
            'bmi' => 'Bmi',
            'keluhan_utama' => 'Keluhan Utama',
            'anamnesis' => 'S (Subyekif)',
            'pemeriksaan_fisik' => 'O (Obyektif)',
            'hasil_penunjang' => 'Hasil Penunjang (jika ada)',
            'deskripsi_tindakan' => 'Deskripsi Tindakan (jika ada)',
            'saran_pemeriksaan' => 'Rencana Pemeriksaan (jika ada)',
            'alergi_obat' => 'Alergi Obat (jika ada)',
            'created' => 'Tanggal RM',
            'modified' => 'Modified',
            'assesment' => 'A (Assesment)',
            'plan' => 'P (Plan)',
            'rl_35_1'=>'Berat Bayi (Gram)',
            'rl_35_2'=>'Kematian Perinatal',
            'rl_35_3'=>'Sebab Kematian',
            'rl_32'=>'Jenis Pelayanan',
            'rl_33'=>'Jenis Kegiatan',
            'rl_34'=>'Jenis Kegiatan',
        ];
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKunjungan()
    {
        return $this->hasOne(Kunjungan::className(), ['kunjungan_id' => 'kunjungan_id']);
    }

    public function getDokter0()
    {
        return $this->hasOne(Dokter::className(), ['user_id' => 'user_id']);
    }

    public function getDpjp()
    {
        return $this->hasOne(Dokter::className(), ['user_id' => 'dpjp'])->viaTable('kunjungan', ['kunjungan_id' => 'kunjungan_id']);
    }

    public function getRuang()
    {
        return $this->hasOne(Ruang::className(), ['ruang_cd' => 'ruang_cd'])->viaTable('kunjungan', ['kunjungan_id' => 'kunjungan_id']);
    }

    public function getPoli()
    {
        return $this->hasOne(UnitMedis::className(), ['medunit_cd' => 'medunit_cd'])->viaTable('kunjungan', ['kunjungan_id' => 'kunjungan_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRmDiagnoses()
    {
        return $this->hasMany(RmDiagnosis::className(), ['rm_id' => 'rm_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRmDiagnosisBandings()
    {
        return $this->hasMany(RmDiagnosisBanding::className(), ['rm_id' => 'rm_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRmObats()
    {
        return $this->hasMany(RmObat::className(), ['rm_id' => 'rm_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRmObatRaciks()
    {
        return $this->hasMany(RmObatRacik::className(), ['rm_id' => 'rm_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRmTindakans()
    {
        return $this->hasMany(RmTindakan::className(), ['rm_id' => 'rm_id']);
    }

    public function reachMaxRm()
    {
        $rm_count = $this->find()->joinWith('kunjungan')->where(['klinik_id'=>Yii::$app->user->identity->klinik_id])->count();
        $klinik = Klinik::findOne(Yii::$app->user->identity->klinik_id);
        if($rm_count<=$klinik->maximum_row){
            return false;
        }
        return true;
    }

    public function getAge($birthDate)
    {
        if(empty($birthDate)) return 0;
        $birthDate = explode("-", $birthDate);
        $age = (date("md", date("U", mktime(0, 0, 0, $birthDate[1], $birthDate[2], $birthDate[0]))) > date("md")
          ? ((date("Y") - $birthDate[0]) - 1)
          : (date("Y") - $birthDate[0]));
        return $age;
    }

    public function getRmInap()
    {
        return $this->hasMany(RmInap::className(), ['rm_id' => 'rm_id']);
    }

    public function getRmInapGizi()
    {
        return $this->hasMany(RmInapGizi::className(), ['rm_id' => 'rm_id']);
    }
}
