<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "inv_unit".
 *
 * @property string $unit_cd
 * @property string $unit_nm
 * @property string $modi_id
 * @property string $modi_datetime
 *
 * @property InvItemMaster[] $invItemMasters
 */
class InvUnit extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'inv_unit';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['unit_cd'], 'required'],
            [['modi_datetime'], 'safe'],
            [['unit_cd', 'modi_id'], 'string', 'max' => 20],
            [['unit_nm'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'unit_cd' => 'Kode',
            'unit_nm' => 'Nama',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvItemMasters()
    {
        return $this->hasMany(InvItemMaster::className(), ['unit_cd' => 'unit_cd']);
    }
}
