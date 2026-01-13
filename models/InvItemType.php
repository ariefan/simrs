<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "inv_item_type".
 *
 * @property string $type_cd
 * @property string $type_nm
 * @property string $modi_id
 * @property string $modi_datetime
 *
 * @property InvItemMaster[] $invItemMasters
 */
class InvItemType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'inv_item_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type_cd'], 'required'],
            [['modi_datetime'], 'safe'],
            [['type_cd', 'modi_id'], 'string', 'max' => 20],
            [['type_nm'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'type_cd' => 'Kode',
            'type_nm' => 'Nama',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvItemMasters()
    {
        return $this->hasMany(InvItemMaster::className(), ['type_cd' => 'type_cd']);
    }
}
