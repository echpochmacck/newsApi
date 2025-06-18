<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string|null $token
 *
 * @property Favorite[] $favorites
 */
class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    const SCENARIO_REGISTER = 'register';

    public object|null $file = null;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['token'], 'default', 'value' => null],
            [['email', 'password'], 'required'],
            ['email', 'email', 'on' => self::SCENARIO_REGISTER],
            ['email', 'unique', 'on' => self::SCENARIO_REGISTER],
            ['password', 'match', 'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).{4,}$/', 'on' => self::SCENARIO_REGISTER],
            ['file', 'file', 'extensions' => 'jpeg, jpg, png', 'maxSize' => 3 * 1024 * 1024, 'skipOnEmpty' => true],
            ['name', 'required', 'on' => self::SCENARIO_REGISTER],
            [['name', 'email', 'password', 'token'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'email' => 'Email',
            'password' => 'Password',
            'token' => 'Token',
        ];
    }

    /**
     * Gets query for [[Favorites]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFavorites()
    {
        return $this->hasMany(Favorite::class, ['user_id' => 'id']);
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['token' => $token]);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        // return $this->authKey;
    }

    public function validateAuthKey($authKey)
    {
        // return $this->authKey === $authKey;
    }
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }
    public function upload()
    {
        $path = Yii::$app->security->generateRandomString() . substr(time(), -5, 2) . ".{$this->file->extension}";
        $this->file->saveAs('uploads/' . $path);
        return $path;
    }
}
