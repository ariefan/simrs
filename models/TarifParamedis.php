<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tarif_paramedis".
 *
 * @property string $tarif_paramedis_id
 * @property string $insurance_cd
 * @property string $kelas_cd
 * @property string $specialis_cd
 * @property string $paramedis_tp
 * @property string $tarif
 * @property string $account_cd
 * @property string $modi_id
 * @property string $modi_datetime
 *
 * @property Kelas $kelasCd
 */
class TarifParamedis extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tarif_paramedis';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tarif_paramedis_id','insurance_cd', 'kelas_cd', 'specialis_cd', 'account_cd'], 'required'],
            [['tarif'], 'number'],
            [['modi_datetime'], 'safe'],
            [['insurance_cd', 'kelas_cd', 'specialis_cd', 'paramedis_tp', 'account_cd', 'modi_id'], 'string', 'max' => 20],
            [['kelas_cd'], 'exist', 'skipOnError' => true, 'targetClass' => Kelas::className(), 'targetAttribute' => ['kelas_cd' => 'kelas_cd']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tarif_paramedis_id' => 'Tarif Paramedis ID',
            'insurance_cd' => 'Insurance Cd',
            'kelas_cd' => 'Kelas Cd',
            'specialis_cd' => 'Specialis Cd',
            'paramedis_tp' => 'Paramedis Tp',
            'tarif' => 'Tarif',
            'account_cd' => 'Account Cd',
            'modi_id' => 'Modi ID',
            'modi_datetime' => 'Modi Datetime',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKelasCd()
    {
        return $this->hasOne(Kelas::className(), ['kelas_cd' => 'kelas_cd']);
    }

    public function getIdInsert()
    {
        if ($this->tarif_paramedis_id)
            return $this->tarif_paramedis_id;
        
        $now = @(int)ltrim(TarifParamedis::find()->orderBy(['tarif_paramedis_id'=>SORT_DESC])->one()->tarif_paramedis_id, 'P');

        return 'P'.str_pad($now+1, 5,'0',STR_PAD_LEFT);
    }
}
