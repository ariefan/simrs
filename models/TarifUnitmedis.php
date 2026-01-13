<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tarif_unitmedis".
 *
 * @property string $tarif_unitmedis_id
 * @property string $insurance_cd
 * @property string $kelas_cd
 * @property string $medicalunit_cd
 * @property string $tarif
 * @property string $account_cd
 * @property string $modi_id
 * @property string $modi_datetime
 *
 * @property UnitMedisItem $medicalunitCd
 */
class TarifUnitmedis extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tarif_unitmedis';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tarif'], 'number'],
            [['modi_datetime'], 'safe'],
            [['insurance_cd', 'kelas_cd', 'medicalunit_cd', 'account_cd', 'modi_id'], 'string', 'max' => 20],
            [['medicalunit_cd'], 'exist', 'skipOnError' => true, 'targetClass' => UnitMedisItem::className(), 'targetAttribute' => ['medicalunit_cd' => 'medicalunit_cd']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tarif_unitmedis_id' => 'Tarif Unitmedis ID',
            'insurance_cd' => 'Insurance Cd',
            'kelas_cd' => 'Kelas Cd',
            'medicalunit_cd' => 'Medicalunit Cd',
            'tarif' => 'Tarif',
            'account_cd' => 'Account Cd',
            'modi_id' => 'Modi ID',
            'modi_datetime' => 'Modi Datetime',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMedicalunitCd()
    {
        return $this->hasOne(UnitMedisItem::className(), ['medicalunit_cd' => 'medicalunit_cd']);
    }

    public function getIdInsert()
    {
        if ($this->tarif_unitmedis_id)
            return $this->tarif_unitmedis_id;
        
        $now = @(int)ltrim(TarifUnitmedis::find()->orderBy(['tarif_unitmedis_id'=>SORT_DESC])->one()->tarif_unitmedis_id, 'U');
        return 'U'.str_pad($now+1, 5,'0',STR_PAD_LEFT);
    }
}
