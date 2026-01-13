<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rl_grouping_310".
 *
 * @property string $rl_ref_310_no
 * @property string $tindakan_cd
 *
 * @property Tindakan $tindakanCd
 */
class RlGrouping310 extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rl_grouping_310';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rl_ref_310_no', 'tindakan_cd'], 'required'],
            [['rl_ref_310_no'], 'string', 'max' => 30],
            [['tindakan_cd'], 'string', 'max' => 11],
            [['tindakan_cd'], 'exist', 'skipOnError' => true, 'targetClass' => Tindakan::className(), 'targetAttribute' => ['tindakan_cd' => 'tindakan_cd']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'rl_ref_310_no' => 'Rl Ref 310 No',
            'tindakan_cd' => 'Tindakan Cd',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTindakanCd()
    {
        return $this->hasOne(Tindakan::className(), ['tindakan_cd' => 'tindakan_cd']);
    }
}
