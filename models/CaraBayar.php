<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cara_bayar".
 *
 * @property integer $cara_id
 * @property string $cara_nama
 * @property string $cara_tipe
 *
 * @property Kunjungan[] $kunjungans
 */
class CaraBayar extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $harga_obat;
    public static function tableName()
    {
        return 'cara_bayar';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cara_tipe'], 'string'],
            [['cara_nama'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cara_id' => 'Cara ID',
            'cara_nama' => 'Cara Nama',
            'cara_tipe' => 'Cara Tipe',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKunjungans()
    {
        return $this->hasMany(Kunjungan::className(), ['cara_id' => 'cara_id']);
    }
}
