<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "unit_medis".
 *
 * @property string $medunit_cd
 * @property string $medunit_nm
 * @property string $medunit_tp
 *
 * @property Jadwal[] $jadwals
 * @property UnitMedisItem[] $unitMedisItems
 */
class UnitMedis extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'unit_medis';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['medunit_cd'], 'required'],
            [['medunit_tp'], 'string'],
            [['medunit_cd'], 'string', 'max' => 20],
            [['medunit_nm'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'medunit_cd' => 'Kode Unit Medis',
            'medunit_nm' => 'Nama Unit Medis',
            'medunit_tp' => 'Tipe Unit Medis',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJadwals()
    {
        return $this->hasMany(Jadwal::className(), ['medunit_cd' => 'medunit_cd']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnitMedisItems()
    {
        return $this->hasMany(UnitMedisItem::className(), ['medunit_cd' => 'medunit_cd']);
    }
}
