<?php

namespace app\models;
use Yii;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property integer $klinik_id
 * @property integer $status
 * @property integer $role
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Dokter $dokter
 * @property RekamMedis[] $rekamMedis
 * @property Klinik $klinik
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    /**
     * @inheritdoc
     */

    public $password;

    const ROLE_ADMIN = 10;
    const ROLE_DOKTER_ADMIN = 20;
    const ROLE_DOKTER = 25;
    const ROLE_PENDAFTARAN = 11;
    const ROLE_KASIR = 12;
    const ROLE_FARMASI = 13;
    const ROLE_RM = 14;
    const ROLE_LAB = 15;
    const ROLE_RAD = 16;
    const ROLE_PETUGAS_RUANG = 17;
    const ROLE_CODING = 18;
    const ROLE_INVENTORY = 19;
    const ROLE_GIZI = 22;

    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password_hash'], 'required'],
            [['klinik_id', 'status', 'role'], 'integer'],
            [['created_at', 'updated_at','bangsal_cd','medunit_cd'], 'safe'],
            [['username', 'password_hash', 'password_reset_token', 'email'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['username'], 'unique'],
            [['password_reset_token'], 'unique'],
            [['klinik_id'], 'exist', 'skipOnError' => true, 'targetClass' => Klinik::className(), 'targetAttribute' => ['klinik_id' => 'klinik_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'apps' => 'Apps',
            'apps_id' => 'ID Apps',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password',
            'password_reset_token' => 'Password Reset Token',
            'email' => 'Email',
            'klinik_id' => 'Klinik / RS',
            'status' => 'Status',
            'role' => 'Role',
            'bangsal_cd' => 'Bangsal',
            'medunit_cd' => 'Unit',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */


    public function getPeran()
    {
        return $this->hasOne(Role::className(), ['id' => 'role']);
    }

    public function getDokter()
    {
        return $this->hasOne(Dokter::className(), ['user_id' => 'id']);
    }

    public function getBangsal()
    {
        return $this->hasOne(Bangsal::className(), ['bangsal_cd' => 'bangsal_cd']);
    }

    public function getUnit()
    {
        return $this->hasOne(UnitMedis::className(), ['medunit_cd' => 'medunit_cd']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRekamMedis()
    {
        return $this->hasMany(RekamMedis::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKlinik()
    {
        return $this->hasOne(Klinik::className(), ['klinik_id' => 'klinik_id']);
    }

    /** INCLUDE USER LOGIN VALIDATION FUNCTIONS**/
    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * @inheritdoc
     */

    public static function findIdentityByAccessToken($token, $type = null)
    {
          return static::findOne(['access_token' => $token]);
    }
 

    /**
     * Finds user by username
     *
     * @param  string      $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    /**
     * Finds user by password reset token
     *
     * @param  string      $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        $expire = \Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        if ($timestamp + $expire < time()) {
            // token expired
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public function getRuang()
    {
        return $this->hasOne(Ruang::className(), ['id' => 'ruang_id']);
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param  string  $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomKey();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomKey() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
}
