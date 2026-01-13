<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "pasien".
 *
 * @property string $mr
 * @property integer $klinik_id
 * @property string $nama
 * @property string $identitas
 * @property string $no_identitas
 * @property string $region_cd
 * @property string $warga_negara
 * @property string $tanggal_lahir
 * @property string $jk
 * @property string $alamat
 * @property string $kode_pos
 * @property string $no_telp
 * @property string $pendidikan
 * @property string $pekerjaan
 * @property string $suku
 * @property string $agama
 * @property string $pj_nama
 * @property string $gol_darah
 * @property integer $berat
 * @property integer $tinggi
 * @property string $pj_hubungan
 * @property string $pj_alamat
 * @property string $pj_telpon
 * @property string $nama_ayah
 * @property string $nama_ibu
 * @property string $email
 * @property string $foto
 * @property string $created
 * @property string $modified
 * @property string $user_input
 * @property string $user_modified
 * @property string $no_asuransi

 * @property Kunjungan[] $kunjungans
 * @property Klinik $klinik
 * @property RekamMedis[] $rekamMedis
 */
class Pasien extends \app\models\bridging\Eklaim //\yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $file; public $tgl_1; public $tgl_2;

    public static function tableName()
    {
        return 'pasien';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mr','nama','tanggal_lahir','alamat','tempat_lahir','pj_nama', 'nama_ayah', 'nama_ibu','identitas','no_identitas'], 'required'],
            [['klinik_id', 'berat', 'tinggi'], 'integer'],
            [['tanggal_lahir', 'created', 'modified'], 'safe'],
            [['jk', 'alamat', 'foto'], 'string'],
            [['mr'], 'string', 'max' => 25],
            [['nama', 'pj_alamat', 'nama_ayah', 'nama_ibu', 'email'], 'string', 'max' => 255],
            [['identitas', 'no_telp', 'pendidikan'], 'string', 'max' => 50],
            
            [['no_identitas', 'warga_negara', 'pekerjaan', 'suku', 'agama', 'pj_nama', 'pj_hubungan', 'pj_telpon', 'user_input', 'user_modified','no_asuransi','jenis_asuransi','tempat_lahir'], 'string', 'max' => 100],
            [['region_cd','suku'], 'string', 'max' => 20],
            [['kode_pos'], 'string', 'max' => 15],
            [['gol_darah'], 'string', 'max' => 5],
            [['klinik_id'], 'exist', 'skipOnError' => true, 'targetClass' => Klinik::className(), 'targetAttribute' => ['klinik_id' => 'klinik_id']],
            [['file'],'file'],
            [['mr'],'unique'],
            [['foto'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'mr' => 'Kode Pasien',
            'klinik_id' => 'Klinik ID',
            'nama' => 'Nama',
            'identitas' => 'Identitas',
            'no_identitas' => 'No Identitas',
            'region_cd' => 'Kode Wilayah',
            'warga_negara' => 'Warga Negara',
            'tanggal_lahir' => 'Tanggal Lahir',
            'jk' => 'Jenis Kelamin',
            'alamat' => 'Alamat',
            'kode_pos' => 'Kode Pos',
            'no_telp' => 'No. Telp',
            'pendidikan' => 'Pendidikan',
            'pekerjaan' => 'Pekerjaan',
            'suku_id' => 'Suku Bangsa',
            'agama' => 'Agama',
            'pj_nama' => 'Nama Penanggung Jawab Pasien',
            'gol_darah' => 'Gol Darah',
            'berat' => 'Berat',
            'tinggi' => 'Tinggi',
            'pj_hubungan' => 'Hubungan Penanggung Jawab dengan Pasien',
            'pj_alamat' => 'Alamat Penanggung Jawab Pasien',
            'pj_telpon' => 'No. Telp. Penanggung Jawab Pasien',
            'nama_ayah' => 'Nama Ayah Pasien',
            'nama_ibu' => 'Nama Ibu Pasien',
            'email' => 'Email',
            'foto' => 'Foto',
            'file' => 'Foto',
            'created' => 'Created',
            'modified' => 'Modified',
            'user_input' => 'User Input',
            'user_modified' => 'User Modified',
            'no_asuransi' => 'No. Asuransi',
            'jenis_asuransi' => 'Jenis Asuransi',
            'tgl_1' => 'Sejak',
            'tgl_2' => 'Sampai'
        ];
    }

    public function generateKode_Pasien(){

        $temp = Pasien::findBySql("SELECT MAX(mr)+1 AS kode FROM pasien")->asArray()->all();
        if($temp[0]['kode'] != null){
            $no = $temp[0]['kode'];
        }else{
            $no = "000000";
        }

        return $no;
    }

    public function simpanFoto(){
        if($this->validate()){
            $src = 'img/pasien/'.$this->mr;
            $ext = $this->file->extension;
            $this->file->saveAs("$src.$ext");

            return true;
        }else{

            return false;
        }
        
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTanggalAwal(){
        return $this->tgl_1;
    }

    public function getTanggalAkhir(){
        return $this->tgl_2;
    }

    public function getKunjungans()
    {
        return $this->hasMany(Kunjungan::className(), ['mr' => 'mr']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKlinik()
    {
        return $this->hasOne(Klinik::className(), ['klinik_id' => 'klinik_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRekamMedis()
    {
        return $this->hasMany(RekamMedis::className(), ['mr' => 'mr']);
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
    
    public function getUmur()
    {
        $birthDate = $this->tanggal_lahir;
        if(empty($birthDate)) return 0;
        $birthDate = explode("-", $birthDate);
        $age = (date("md", date("U", mktime(0, 0, 0, $birthDate[1], $birthDate[2], $birthDate[0]))) > date("md")
          ? ((date("Y") - $birthDate[0]) - 1)
          : (date("Y") - $birthDate[0]));
        return $age." Tahun";
    }
}