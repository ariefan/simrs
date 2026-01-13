<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tarif_general".
 *
 * @property integer $tarif_general_id
 * @property string $tarif_nm
 * @property string $insurance_cd
 * @property string $kelas_cd
 * @property string $tarif
 * @property string $auto_add
 * @property string $medical_tp
 * @property string $account_cd
 * @property string $modi_id
 * @property string $modi_datetime
 *
 * @property Account $accountCd
 * @property Kelas $kelasCd
 * @property Asuransi $insuranceCd
 */
class TarifGeneral extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */

    public static $pendaftaranPeserta = "G00000";
    public static function tableName()
    {
        return 'tarif_general';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['account_cd','kelas_cd','insurance_cd','tarif_general_id'],'required'],
            [['tarif'], 'number'],
            [['modi_datetime'], 'safe'],
            [['tarif_nm'], 'string', 'max' => 100],
            [['insurance_cd', 'kelas_cd', 'medical_tp', 'account_cd', 'modi_id'], 'string', 'max' => 20],
            [['auto_add'], 'string', 'max' => 1],
            [['account_cd'], 'exist', 'skipOnError' => true, 'targetClass' => Account::className(), 'targetAttribute' => ['account_cd' => 'account_cd']],
            [['kelas_cd'], 'exist', 'skipOnError' => true, 'targetClass' => Kelas::className(), 'targetAttribute' => ['kelas_cd' => 'kelas_cd']],
            [['insurance_cd'], 'exist', 'skipOnError' => true, 'targetClass' => Asuransi::className(), 'targetAttribute' => ['insurance_cd' => 'insurance_cd']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tarif_general_id' => 'Tarif General ID',
            'tarif_nm' => 'Tarif Nm',
            'insurance_cd' => 'Insurance Cd',
            'kelas_cd' => 'Kelas Cd',
            'tarif' => 'Tarif',
            'auto_add' => 'Auto Add',
            'medical_tp' => 'Medical Tp',
            'account_cd' => 'Account Cd',
            'modi_id' => 'Modi ID',
            'modi_datetime' => 'Modi Datetime',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountCd()
    {
        return $this->hasOne(Account::className(), ['account_cd' => 'account_cd']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKelasCd()
    {
        return $this->hasOne(Kelas::className(), ['kelas_cd' => 'kelas_cd']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInsuranceCd()
    {
        return $this->hasOne(Asuransi::className(), ['insurance_cd' => 'insurance_cd']);
    }

    public function getIdInsert()
    {
        if ($this->tarif_general_id)
            return $this->tarif_general_id;
        
        $now = @(int)ltrim(TarifGeneral::find()->orderBy(['tarif_general_id'=>SORT_DESC])->one()->tarif_general_id, 'G');

        return 'G'.str_pad($now+1, 5,'0',STR_PAD_LEFT);
    }
}
