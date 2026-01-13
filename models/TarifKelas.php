<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tarif_kelas".
 *
 * @property string $seq_no
 * @property string $insurance_cd
 * @property string $kelas_cd
 * @property string $tarif
 * @property string $account_cd
 * @property string $modi_id
 * @property string $modi_datetime
 *
 * @property Kelas $kelasCd
 */
class TarifKelas extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tarif_kelas';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['seq_no'], 'string'],
            [['tarif'], 'number'],
            [['modi_datetime'], 'safe'],
            [['insurance_cd', 'kelas_cd', 'account_cd', 'modi_id'], 'string', 'max' => 20],
            [['kelas_cd'], 'exist', 'skipOnError' => true, 'targetClass' => Kelas::className(), 'targetAttribute' => ['kelas_cd' => 'kelas_cd']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'seq_no' => 'Seq No',
            'insurance_cd' => 'Insurance Cd',
            'kelas_cd' => 'Kelas Cd',
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
        if ($this->seq_no)
            return $this->seq_no;
        
        $now = @(int)ltrim(TarifKelas::find()->orderBy(['seq_no'=>SORT_DESC])->one()->seq_no, 'K');
        return 'K'.str_pad($now+1, 5,'0',STR_PAD_LEFT);
    }
}
