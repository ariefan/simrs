<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "account_group".
 *
 * @property string $accgroup_cd
 * @property string $accgroup_nm
 * @property integer $order_no
 */
class AccountGroup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'account_group';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['accgroup_cd'], 'required'],
            [['order_no'], 'integer'],
            [['accgroup_cd'], 'string', 'max' => 20],
            [['accgroup_nm'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'accgroup_cd' => 'Accgroup Cd',
            'accgroup_nm' => 'Accgroup Nm',
            'order_no' => 'Order No',
        ];
    }
}
