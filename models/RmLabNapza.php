<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rm_lab_napza".
 *
 * @property integer $lab_napza_id
 * @property string $rm_id
 * @property string $nomor_surat
 * @property string $tanggal_surat
 * @property string $permintaan
 * @property string $keperluan
 * @property string $tanggal_periksa
 * @property string $created
 *
 * @property RekamMedis $rm
 * @property RmLabNapzaDetail[] $rmLabNapzaDetails
 */
class RmLabNapza extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rm_lab_napza';
    }

    /**
     * @inheritdoc
     */

    public $hasils;
    public function rules()
    {
        return [
            ['hasils', 'each', 'rule' => ['string']],
            [['rm_id'], 'integer'],
            [['tanggal_surat', 'rm_id', 'permintaan','nomor_surat', 'keperluan'], 'required'],
            [['tanggal_surat', 'tanggal_periksa', 'created'], 'safe'],
            [['keperluan'], 'string'],
            [['nomor_surat', 'permintaan'], 'string', 'max' => 255],
            [['rm_id'], 'exist', 'skipOnError' => true, 'targetClass' => RekamMedis::className(), 'targetAttribute' => ['rm_id' => 'rm_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'lab_napza_id' => 'Lab Napza ID',
            'rm_id' => 'Rm ID',
            'nomor_surat' => 'Nomor Surat',
            'tanggal_surat' => 'Tanggal Surat',
            'permintaan' => 'Permintaan',
            'keperluan' => 'Keperluan',
            'tanggal_periksa' => 'Tanggal Periksa',
            'hasils' => 'Hasil Tes',
            'created' => 'Created',
            'rm.kunjungan.created' => 'Tanggal Kunjungan',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRm()
    {
        return $this->hasOne(RekamMedis::className(), ['rm_id' => 'rm_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRmLabNapzaDetails()
    {
        return $this->hasMany(RmLabNapzaDetail::className(), ['lab_napza_id' => 'lab_napza_id']);
    }

    public function getViewHasils(){
        $mdl = $this->rmLabNapzaDetails;
        $e = '';
        foreach ($mdl as $key => $value) {
            $e .= $value->periksa->periksa_nama." : ";
            $e .= ($value->hasil == 1)? "<strong>Positif</strong>":"<strong>Negatif</strong>";
            $e .= "<br/>";
        }
        return $e;
    }
}
