<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ref_suku".
 *
 * @property integer $suku_id
 * @property string $suku_nama
 *
 * @property Pasien[] $pasiens
 */
class RefSuku extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ref_suku';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['suku_nama'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'suku_id' => 'Suku ID',
            'suku_nama' => 'Suku Nama',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPasiens()
    {
        return $this->hasMany(Pasien::className(), ['suku_id' => 'suku_id']);
    }
}
