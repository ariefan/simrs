<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rl_ref_4_luar".
 *
 * @property string $no_dtd
 * @property string $diagnosa_ref
 * @property string $sebab_penyakit
 */
class RlRef4Luar extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rl_ref_4_luar';
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
            'diagnosa_ref' => 'Diagnosa Ref',
            'sebab_penyakit' => 'Sebab Penyakit',
        ];
    }
}
