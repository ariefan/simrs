<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rm_obat_racik_komponen".
 *
 * @property string $id
 * @property integer $racik_id
 * @property integer $obat_id
 * @property integer $jumlah
 *
 * @property RmObatRacik $racik
 * @property Obat $obat
 */
class RmObatRacikKomponen extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rm_obat_racik_komponen';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['racik_id', 'jumlah','proses_stok','batch_no'], 'integer'],
            [['nama_obat','satuan', 'obat_id'],'string','max'=>255],
            [['racik_id'], 'exist', 'skipOnError' => true, 'targetClass' => RmObatRacik::className(), 'targetAttribute' => ['racik_id' => 'racik_id']],
            [['obat_id'], 'exist', 'skipOnError' => true, 'targetClass' => InvItemMaster::className(), 'targetAttribute' => ['obat_id' => 'item_cd']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'racik_id' => 'Racik ID',
            'obat_id' => 'Obat ID',
            'satuan' => 'Satuan',
            'jumlah' => 'Jumlah',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRacik()
    {
        return $this->hasOne(RmObatRacik::className(), ['racik_id' => 'racik_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getObat()
    {
        return $this->hasOne(Obat::className(), ['obat_id' => 'obat_id']);
    }
}
