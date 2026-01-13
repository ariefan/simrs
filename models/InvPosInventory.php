<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "inv_pos_inventory".
 *
 * @property string $pos_cd
 * @property string $pos_nm
 * @property string $pos_root
 * @property string $description
 * @property string $unit_medis
 * @property string $modi_id
 * @property string $modi_datetime
 *
 * @property InvItemMove[] $invItemMoves
 * @property InvItemMove[] $invItemMoves0
 * @property InvPosItem[] $invPosItems
 * @property InvItemMaster[] $itemCds
 */
class InvPosInventory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'inv_pos_inventory';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pos_cd'], 'required'],
            [['description'], 'string'],
            [['modi_datetime'], 'safe'],
            [['pos_cd', 'pos_root', 'unit_medis', 'modi_id'], 'string', 'max' => 20],
            [['pos_nm'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'pos_cd' => 'Kode',
            'pos_nm' => 'Nama',
            'description' => 'Deskripsi',
            'unit_medis' => 'Unit Medis',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvItemMoves()
    {
        return $this->hasMany(InvItemMove::className(), ['pos_cd' => 'pos_cd']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvItemMoves0()
    {
        return $this->hasMany(InvItemMove::className(), ['pos_destination' => 'pos_cd']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvPosItems()
    {
        return $this->hasMany(InvPosItem::className(), ['pos_cd' => 'pos_cd']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItemCds()
    {
        return $this->hasMany(InvItemMaster::className(), ['item_cd' => 'item_cd'])->viaTable('inv_pos_item', ['pos_cd' => 'pos_cd']);
    }

    public function getBangsal(){
        return $this->hasOne(Bangsal::classname(),['bangsal_cd'=>'unit_medis']);
    }

    public function getUnitMedis(){
        return $this->hasOne(UnitMedis::classname(),['medunit_cd'=>'unit_medis']);
    }
}
