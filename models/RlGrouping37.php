<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rl_grouping_37".
 *
 * @property string $rl_ref_37_no
 * @property string $medicalunit_cd
 *
 * @property UnitMedisItem $medicalunitCd
 */
class RlGrouping37 extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rl_grouping_37';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rl_ref_37_no', 'medicalunit_cd'], 'required'],
            [['rl_ref_37_no'], 'string', 'max' => 30],
            [['medicalunit_cd'], 'string', 'max' => 20],
            [['medicalunit_cd'], 'exist', 'skipOnError' => true, 'targetClass' => UnitMedisItem::className(), 'targetAttribute' => ['medicalunit_cd' => 'medicalunit_cd']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'rl_ref_37_no' => 'Rl Ref 37 No',
            'medicalunit_cd' => 'Medicalunit Cd',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMedicalunitCd()
    {
        return $this->hasOne(UnitMedisItem::className(), ['medicalunit_cd' => 'medicalunit_cd']);
    }
}
