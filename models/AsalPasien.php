<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "asal_pasien".
 *
 * @property integer $asal_id
 * @property string $asal_nama
 *
 * @property Kunjungan[] $kunjungans
 */
class AsalPasien extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'asal_pasien';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['asal_nama'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'asal_id' => 'Asal ID',
            'asal_nama' => 'Asal Nama',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKunjungans()
    {
        return $this->hasMany(Kunjungan::className(), ['asal_id' => 'asal_id']);
    }
}
