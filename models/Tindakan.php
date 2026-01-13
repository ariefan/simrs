<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tindakan".
 *
 * @property string $tindakan_cd
 * @property string $tindakan_root
 * @property integer $klinik_id
 * @property string $nama_tindakan
 * @property integer $total_tarif
 * @property string $created
 * @property string $modified
 * @property integer $biaya_wajib
 *
 * @property BayarTindakan[] $bayarTindakans
 * @property RmTindakan[] $rmTindakans
 * @property TarifTindakan[] $tarifTindakans
 * @property Klinik $klinik
 * @property TindakanLog[] $tindakanLogs
 */
class Tindakan extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tindakan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tindakan_cd'], 'required'],
            [['klinik_id', 'total_tarif', 'biaya_wajib'], 'integer'],
            [['created', 'modified'], 'safe'],
            [['tindakan_cd', 'tindakan_root'], 'string', 'max' => 11],
            [['nama_tindakan'], 'string', 'max' => 500],
            [['klinik_id'], 'exist', 'skipOnError' => true, 'targetClass' => Klinik::className(), 'targetAttribute' => ['klinik_id' => 'klinik_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tindakan_cd' => 'Tindakan Cd',
            'tindakan_root' => 'Tindakan Root',
            'klinik_id' => 'Klinik ID',
            'nama_tindakan' => 'Nama Tindakan',
            'total_tarif' => 'Total Tarif',
            'created' => 'Created',
            'modified' => 'Modified',
            'biaya_wajib' => 'Biaya Wajib',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBayarTindakans()
    {
        return $this->hasMany(BayarTindakan::className(), ['tindakan_cd' => 'tindakan_cd']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRmTindakans()
    {
        return $this->hasMany(RmTindakan::className(), ['tindakan_cd' => 'tindakan_cd']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTarifTindakan()
    {
        return $this->hasMany(TarifTindakan::className(), ['treatment_cd' => 'tindakan_cd']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKlinik()
    {
        return $this->hasOne(Klinik::className(), ['klinik_id' => 'klinik_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTindakanLogs()
    {
        return $this->hasMany(TindakanLog::className(), ['tindakan_cd' => 'tindakan_cd']);
    }

    public static function getDataTindakan(){
        $sql = "SELECT 
                  a.`tindakan_cd` AS id,
                  CONCAT(
                    a.`nama_tindakan`,
                    ' - (',
                    LOWER(b.`nama_tindakan`),')'
                  ) AS text 
                FROM
                  tindakan AS a 
                  LEFT JOIN tindakan AS b 
                    ON a.`tindakan_root` = b.`tindakan_cd` 
                WHERE a.tindakan_root IS NOT NULL";
        $connection = Yii::$app->db;
        $command = $connection->createCommand($sql);
        return $command->queryAll();
    }
}
