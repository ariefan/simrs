<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sensus".
 *
 * @property string $mr
 * @property string $nama
 * @property string $alamat
 * @property string $jk
 * @property integer $umur
 * @property string $kasus
 * @property string $cara_nama
 * @property string $vaksin
 * @property string $kode
 * @property string $nama_diagnosis
 * @property string $asal_nama
 * @property string $konsul_ke
 */
class Sensus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sensus';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mr'], 'required'],
            [['alamat', 'jk', 'kasus', 'vaksin', 'konsul_ke'], 'string'],
            [['umur'], 'integer'],
            [['mr'], 'string', 'max' => 25],
            [['nama', 'cara_nama', 'nama_diagnosis', 'asal_nama'], 'string', 'max' => 255],
            [['kode'], 'string', 'max' => 15],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'mr' => 'Mr',
            'nama' => 'Nama',
            'alamat' => 'Alamat',
            'jk' => 'Jk',
            'umur' => 'Umur',
            'kasus' => 'Kasus',
            'cara_nama' => 'Cara Nama',
            'vaksin' => 'Vaksin',
            'kode' => 'Kode',
            'nama_diagnosis' => 'Nama Diagnosis',
            'asal_nama' => 'Asal Nama',
            'konsul_ke' => 'Konsul Ke',
        ];
    }
}
