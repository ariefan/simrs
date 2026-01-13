<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rl_ref_4".
 *
 * @property string $no_dtd
 * @property string $sebab_penyakit
 * @property string $diagnosa_ref
 */
class RlRef4 extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rl_ref_4';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['no_dtd'], 'required'],
            [['sebab_penyakit'], 'string'],
            [['no_dtd'], 'string', 'max' => 30],
            [['diagnosa_ref'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'no_dtd' => 'No Dtd',
            'sebab_penyakit' => 'Sebab Penyakit',
            'diagnosa_ref' => 'Diagnosa Ref',
        ];
    }
}
