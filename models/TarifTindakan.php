<?php

namespace app\models;




use Yii;

/**
 * This is the model class for table "tarif_tindakan".
 *
 * @property string $tarif_tindakan_id
 * @property string $insurance_cd
 * @property string $kelas_cd
 * @property string $treatment_cd
 * @property string $tarif
 * @property string $account_cd
 * @property string $modi_id
 * @property string $modi_datetime
 *
 * @property Tindakan $treatmentCd
 */
class TarifTindakan extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tarif_tindakan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tarif_tindakan_id','insurance_cd','kelas_cd','treatment_cd','account_cd'], 'required'],
            [['tarif'], 'number'],
            [['modi_datetime'], 'safe'],
            [['insurance_cd', 'kelas_cd', 'treatment_cd', 'account_cd', 'modi_id'], 'string', 'max' => 20],
            [['treatment_cd'], 'exist', 'skipOnError' => true, 'targetClass' => Tindakan::className(), 'targetAttribute' => ['treatment_cd' => 'tindakan_cd']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tarif_tindakan_id' => 'Tarif Tindakan ID',
            'insurance_cd' => 'Insurance Cd',
            'kelas_cd' => 'Kelas Cd',
            'treatment_cd' => 'Treatment Cd',
            'tarif' => 'Tarif',
            'account_cd' => 'Account Cd',
            'modi_id' => 'Modi ID',
            'modi_datetime' => 'Modi Datetime',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTreatmentCd()
    {
        return $this->hasOne(Tindakan::className(), ['tindakan_cd' => 'treatment_cd']);
    }

    public function getIdInsert()
    {
        if ($this->tarif_tindakan_id)
            return $this->tarif_tindakan_id;
        
        $now = @(int)ltrim(TarifTindakan::find()->orderBy(['tarif_tindakan_id'=>SORT_DESC])->one()->tarif_tindakan_id, 'T');
        return 'T'.str_pad($now+1, 5,'0',STR_PAD_LEFT);
    }
}
