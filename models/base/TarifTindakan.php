<?php

namespace app\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use mootensai\behaviors\UUIDBehavior;

/**
 * This is the base model class for table "tarif_tindakan".
 *
 * @property string $tarif_tindakan_id
 * @property string $insurance_cd
 * @property string $kelas_cd
 * @property string $treatment_cd
 * @property string $tarif
 * @property string $account_cd
 * @property string $modi_id
 * @property string $modi_datetime
 *
 * @property \app\models\Tindakan $treatmentCd
 */
class TarifTindakan extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;

    private $_rt_softdelete;
    private $_rt_softrestore;

    public function __construct(){
        parent::__construct();
        $this->_rt_softdelete = [
            'deleted_by' => \Yii::$app->user->id,
            'deleted_at' => date('Y-m-d H:i:s'),
        ];
        $this->_rt_softrestore = [
            'deleted_by' => 0,
            'deleted_at' => date('Y-m-d H:i:s'),
        ];
    }

    /**
    * This function helps \mootensai\relation\RelationTrait runs faster
    * @return array relation names of this model
    */
    public function relationNames()
    {
        return [
            'treatmentCd'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tarif_tindakan_id'], 'required'],
            [['tarif'], 'number'],
            [['modi_datetime'], 'safe'],
            [['tarif_tindakan_id', 'insurance_cd', 'kelas_cd', 'treatment_cd', 'account_cd', 'modi_id'], 'string', 'max' => 20],
            [['lock'], 'default', 'value' => '0'],
            [['lock'], 'mootensai\components\OptimisticLockValidator']
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tarif_tindakan';
    }

    /**
     *
     * @return string
     * overwrite function optimisticLock
     * return string name of field are used to stored optimistic lock
     *
     */
    public function optimisticLock() {
        return 'lock';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tarif_tindakan_id' => 'Tarif Tindakan ID',
            'insurance_cd' => 'Insurance Cd',
            'kelas_cd' => 'Kelas Cd',
            'treatment_cd' => 'Treatment Cd',
            'tarif' => 'Tarif',
            'account_cd' => 'Account Cd',
            'modi_id' => 'Modi ID',
            'modi_datetime' => 'Modi Datetime',
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTreatmentCd()
    {
        return $this->hasOne(\app\models\Tindakan::className(), ['tindakan_cd' => 'treatment_cd']);
    }
    
    /**
     * @inheritdoc
     * @return array mixed
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new \yii\db\Expression('NOW()'),
            ],
            'blameable' => [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'updated_by',
            ],
            'uuid' => [
                'class' => UUIDBehavior::className(),
                'column' => 'id',
            ],
        ];
    }
}
