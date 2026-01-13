<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;

/**
* This is the model class for table "dokter".
 
* @property string $alamat
* @property string $tanggal_lahir
* @property string $created
* @property string $email 
* @property string $alumni 
* @property string $pekerjaan 
* @property string $kota_id 
* @property string $jenis_kelamin 
*
* @property RefKokab $kota 
* @property User $user
* @property Kunjungan[] $kunjungans 
*/
class Dokter extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $imageFile;
    const SCENARIO_PROFILE = 'update';
    public static function tableName()
    {
        return 'dokter';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'nama', 'no_telp', 'spesialis', 'alamat', 'tanggal_lahir', 'jenis_kelamin','kota_id'], 'required','on'=>self::SCENARIO_PROFILE],
            [['user_id'], 'integer'],
            [['foto', 'alamat'], 'string'],
            [['tanggal_lahir', 'created'], 'safe'],
            [['nama', 'spesialis', 'email', 'alumni', 'pekerjaan'], 'string', 'max' => 255],
            [['no_telp', 'no_telp_2'], 'string', 'max' => 100],
            [['waktu_praktek'], 'string', 'max' => 500],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['kota_id'], 'string', 'max' => 4], 
            [['kota_id'], 'exist', 'skipOnError' => true, 'targetClass' => RefKokab::className(), 'targetAttribute' => ['kota_id' => 'kota_id']], 
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'nama' => 'Nama Dokter',
            'no_telp' => 'No Telp',
            'no_telp_2' => 'No Telp 2',
            'spesialis' => 'Spesialis',
            'waktu_praktek' => 'Waktu Praktek',
            'foto' => 'Foto',
            'alamat' => 'Alamat',
            'tanggal_lahir' => 'Tanggal Lahir',
            'created' => 'Created',
            'email' => 'Email', 
            'alumni' => 'Alumni', 
            'pekerjaan' => 'Pekerjaan', 
            'kota_id' => 'Kota/Kabupaten', 
            'jenis_kelamin' => 'Jenis Kelamin', 
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getKota() 
    { 
       return $this->hasOne(RefKokab::className(), ['kota_id' => 'kota_id']); 
    }

    public function getSpesialisasi() 
    { 
       return $this->hasOne(Spesialis::className(), ['spesialis_id' => 'spesialis']); 
    }

    public function upload()
    {
        if ($this->validate()) {
            $src = 'img/dokter/' . $this->user_id;
            $ext = $this->imageFile->extension;
            $this->imageFile->saveAs("$src.$ext");

            return true;
        } else {
            return false;
        }
    }

    public function isNothingEmpty()
    {
        $cant_be_empty = ['user_id', 'nama', 'no_telp', 'spesialis', 'alamat', 'tanggal_lahir', 'email', 'alumni', 'pekerjaan', 'jenis_kelamin'];
        $data = self::findOne(Yii::$app->user->identity->id);
        foreach($cant_be_empty as $col)
            if(empty($data->$col)) return false;
        return true;        
    }
}
