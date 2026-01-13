<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "region".
 *
 * @property string $region_cd
 * @property string $region_nm
 * @property string $region_root
 * @property string $region_capital
 * @property integer $region_level
 * @property string $default_st
 * @property string $modi_id
 * @property string $modi_datetime
 */
class Region extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $kode_region_baru;

    public static function tableName()
    {
        return 'region';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['region_cd'], 'required'],
            [['region_capital'], 'string'],
            [['region_level'], 'integer'],
            [['modi_datetime'], 'safe'],
            [['region_cd','kode_region_baru', 'region_root', 'modi_id'], 'string', 'max' => 20],
            //['region_root','required','when'=>function($model){
            //    return(!empty($model->region_level))? true:false;
            //    }, 'whenClient'=>"function(){
            //        if($('#region_level').val() === '1')
            //        {
            //            true;
            //       } else{
            //            false;
            //        }
            //    }"
            //    ],
            [['region_nm'], 'string', 'max' => 100],
            [['default_st'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'region_cd' => 'Kode Region',
            'region_level' => 'Region Level', 
            'region_nm' => 'Nama Region',
            'region_root' => 'Region Root',
            'region_capital' => 'Region Capital',
            'default_st' => 'Default St',
            'modi_id' => 'Modi ID',
            'modi_datetime' => 'Modi Datetime',
        ];
    }

    public function buatKodeRegion($id){
        $_left = $id;
        $_first = "01";
        
        $larik = (ArrayHelper::map(Region::find()->where(['region_root' => $id])->all(), 'region_cd', 'region_cd'));
        if(is_null($larik[end($larik)])){
            $no = $_left . $_first;
        }else{
            $no = $larik[end($larik)]+1;
        }

        return $no;
    }

}
