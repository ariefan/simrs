<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "unit_medis_item".
 *
 * @property string $medicalunit_cd
 * @property string $medunit_cd
 * @property string $medicalunit_root
 * @property string $medicalunit_nm
 * @property string $root_st
 * @property string $file_format
 * @property string $modi_id
 * @property string $modi_datetime
 *
 * @property TarifUnitmedis[] $tarifUnitmedis
 * @property UnitMedis $medunitCd
 */
class UnitMedisItem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'unit_medis_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['medicalunit_cd', 'medunit_cd'], 'required'],
            [['modi_datetime'], 'safe'],
            [['medicalunit_cd', 'medunit_cd', 'medicalunit_root', 'modi_id'], 'string', 'max' => 20],
            [['medicalunit_nm', 'file_format'], 'string', 'max' => 100],
            [['root_st'], 'string', 'max' => 1],
            [['medunit_cd'], 'exist', 'skipOnError' => true, 'targetClass' => UnitMedis::className(), 'targetAttribute' => ['medunit_cd' => 'medunit_cd']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'medicalunit_cd' => 'Kode Item Unit Medis',
            'medunit_cd' => 'Kode Unit Medis',
            'medicalunit_root' => 'Medicalunit Root',
            'medicalunit_nm' => 'Nama Item',
            'root_st' => 'Root St',
            'file_format' => 'File Format',
            'modi_id' => 'User Modified',
            'modi_datetime' => 'Time MOdified',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTarifUnitmedis()
    {
        return $this->hasMany(TarifUnitmedis::className(), ['medicalunit_cd' => 'medicalunit_cd']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMedunitCd()
    {
        return $this->hasOne(UnitMedis::className(), ['medunit_cd' => 'medunit_cd']);
    }

    public static function getWithSub($medunit_cd){
        $temp = UnitMedisItem::find()->joinWith('tarifUnitmedis')->where(['medunit_cd'=>$medunit_cd])->orderBy('root_st DESC,medicalunit_nm ASC')->asArray()->all();
        // echo '<pre>';
        // print_r($temp);exit;
        $data = array();
        $data_name = array();
        $data_name[''] = 'LAIN LAIN';
        foreach ($temp as $key => $value) {
            if($value['root_st']=='1'){
                $data_name[$value['medicalunit_cd']] = $value['medicalunit_nm'];

            } else {
                $data[$data_name[$value['medicalunit_root']]][$value['medicalunit_cd']] = $value['medicalunit_cd'].' - '.$value['medicalunit_nm'];
            }
        }
        // print_r($data);exit;
        return $data;
    }
}
