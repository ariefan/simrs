<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rl_ref_36".
 *
 * @property integer $no
 * @property string $spesialisasi
 *
 * @property RlGrouping36[] $rlGrouping36s
 * @property Tindakan[] $tindakanCds
 */
class RlRef36 extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rl_ref_36';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['no'], 'required'],
            [['no'], 'integer'],
            [['spesialisasi'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'no' => 'No',
            'spesialisasi' => 'Spesialisasi',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRlGrouping36s()
    {
        return $this->hasMany(RlGrouping36::className(), ['rl_ref_36_no' => 'no']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTindakanCds()
    {
        return $this->hasMany(Tindakan::className(), ['tindakan_cd' => 'tindakan_cd'])->viaTable('rl_grouping_36', ['rl_ref_36_no' => 'no']);
    }
}
