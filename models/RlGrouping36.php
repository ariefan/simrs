<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rl_grouping_36".
 *
 * @property integer $rl_ref_36_no
 * @property string $tindakan_cd
 * @property string $jenis
 *
 * @property Tindakan $tindakanCd
 * @property RlRef36 $rlRef36No
 */
class RlGrouping36 extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rl_grouping_36';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rl_ref_36_no', 'tindakan_cd'], 'required'],
            [['rl_ref_36_no'], 'integer'],
            [['jenis'], 'string'],
            [['tindakan_cd'], 'string', 'max' => 11],
            [['tindakan_cd'], 'exist', 'skipOnError' => true, 'targetClass' => Tindakan::className(), 'targetAttribute' => ['tindakan_cd' => 'tindakan_cd']],
            [['rl_ref_36_no'], 'exist', 'skipOnError' => true, 'targetClass' => RlRef36::className(), 'targetAttribute' => ['rl_ref_36_no' => 'no']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'rl_ref_36_no' => 'Rl Ref 36 No',
            'tindakan_cd' => 'Tindakan Cd',
            'jenis' => 'Jenis',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTindakanCd()
    {
        return $this->hasOne(Tindakan::className(), ['tindakan_cd' => 'tindakan_cd']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRef()
    {
        return $this->hasOne(RlRef36::className(), ['no' => 'rl_ref_36_no']);
    }
}
