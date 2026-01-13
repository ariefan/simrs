<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "gizi_diet".
 *
 * @property string $kode
 * @property string $nama_diet
 * @property string $deskripsi
 * @property string $created
 * @property string $modified
 *
 * @property GiziMakanan[] $giziMakanans
 */
class GiziDiet extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'gizi_diet';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kode'], 'required'],
            [['deskripsi'], 'string'],
            [['created', 'modified'], 'safe'],
            [['kode'], 'string', 'max' => 15],
            [['nama_diet'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'kode' => 'Kode',
            'nama_diet' => 'Nama Diet',
            'deskripsi' => 'Deskripsi',
            'created' => 'Created',
            'modified' => 'Modified',
            'rm_id' => 'Nama',
            'kunjungan_id' => 'Kunjungan',
        ];
    } 

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGiziMakanans()
    {
        return $this->hasMany(GiziMakanan::className(), ['kode_diet' => 'kode']);
    }
    public function getRmGiziDiet()
    {
        return $this->hasOne(RmInapGizi::className(),['rm_id' => 'kode_diet']);
    }
}
