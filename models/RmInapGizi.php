<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rm_inap_gizi".
 *
 * @property string $id
 * @property string $rm_id
 * @property string $kode_diet
 * @property string $jam_makan
 * @property string $created
 *
 * @property RekamMedis $rm
 * @property GiziDiet $kodeDiet
 */
class RmInapGizi extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $mr_id;
    public static function tableName()
    {
        return 'rm_inap_gizi';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rm_id', 'kode_diet'], 'required'],
            [['rm_id'], 'integer'],
            [['jam_makan','jam_makan_spesifik', 'created','status','diagnosa'], 'safe'],
            [['kode_diet'], 'string', 'max' => 15],
            [['rm_id'], 'exist', 'skipOnError' => true, 'targetClass' => RekamMedis::className(), 'targetAttribute' => ['rm_id' => 'rm_id']],
            [['kode_diet'], 'exist', 'skipOnError' => true, 'targetClass' => GiziDiet::className(), 'targetAttribute' => ['kode_diet' => 'kode']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'mr' => 'Nama',
            'rm_id'=> 'No Rekam Medis',
            'kunjungan_id' => 'Ruangan',
            'user_id' => 'User id',
            'kode_diet' => 'Diet',
            'jam_makan' => 'Jam Makan',
            'jam_makan_spesifik' => 'Spesifik Waktu',
            'status' => 'Status',
            'created' => 'Created',

        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
   
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDiet()
    {
        return $this->hasOne(GiziDiet::className(), ['kode' => 'kode_diet']);
    }
    public function getRekamMedis()
    {
        return $this->hasOne(RekamMedis::className(), ['rm_id' =>'rm_id']);
    }
   public function getMr()
    {
        return $this->hasOne(Pasien::className(), ['mr' => 'mr'])->viaTable('rekam_medis', ['rm_id' => 'rm_id']);
    }
    public function getRuang()
    {
        return $this->hasOne(Kunjungan::className(), ['kunjungan_id' => 'kunjungan_id'])->viaTable('rekam_medis', ['rm_id' => 'rm_id']);
    }
}
