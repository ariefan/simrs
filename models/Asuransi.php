<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "asuransi".
 *
 * @property string $insurance_cd
 * @property string $insurance_nm
 *
 * @property TarifGeneral[] $tarifGenerals
 */
class Asuransi extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'asuransi';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['insurance_cd', 'insurance_nm'], 'required'],
            [['insurance_cd'], 'string', 'max' => 20],
            [['insurance_nm'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'insurance_cd' => 'Insurance Cd',
            'insurance_nm' => 'Insurance Nm',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTarifGenerals()
    {
        return $this->hasMany(TarifGeneral::className(), ['insurance_cd' => 'insurance_cd']);
    }
}