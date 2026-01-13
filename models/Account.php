<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "account".
 *
 * @property string $account_cd
 * @property string $accgroup_cd
 * @property string $account_nm
 * @property string $default_amount
 * @property integer $order_no
 * @property string $print_single_st
 *
 * @property TarifGeneral[] $tarifGenerals
 */
class Account extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'account';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['account_cd'], 'required'],
            [['default_amount'], 'number'],
            [['order_no'], 'integer'],
            [['account_cd', 'accgroup_cd'], 'string', 'max' => 20],
            [['account_nm'], 'string', 'max' => 100],
            [['print_single_st'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'account_cd' => 'Account Cd',
            'accgroup_cd' => 'Accgroup Cd',
            'account_nm' => 'Account Nm',
            'default_amount' => 'Default Amount',
            'order_no' => 'Order No',
            'print_single_st' => 'Print Single St',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTarifGenerals()
    {
        return $this->hasMany(TarifGeneral::className(), ['account_cd' => 'account_cd']);
    }
}
