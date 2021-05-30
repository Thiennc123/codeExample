<?php

namespace app\modules\api\models;

use app\modules\api\models\AppModel;
use app\modules\api\models\Property;
use app\modules\api\models\UserProperty;
use Yii;
use yii\web\IdentityInterface;
use app\modules\api\helpers\PermissionHelper;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string|null $email_verified_at
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property string|null $confirmation_token
 * @property string|null $mobile_phone
 */
class User extends AppModel implements IdentityInterface
{
    public $password_confirmation;
    public $password;
    public $authKey;
    public $property_id;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'email'], 'required'],
            [['email_verified_at', 'created_at', 'updated_at'], 'safe'],
            [['name', 'email', 'password', 'confirmation_token', 'mobile_phone'], 'string', 'max' => 255],
            [['email'], 'unique'],
            [['password_confirmation', 'password'], 'required', 'on' => 'create'],
            [['password', 'password_confirmation'], 'string', 'min' => 6, 'on' => 'create'],
            [['password'], 'compare', 'compareAttribute' => 'password_confirmation', 'on' => 'create'],
        ];
    }

    public function beforeSave($insert)
    {
        if (!empty($this->password)) {
            $this->encrypted_password = Yii::$app->getSecurity()->generatePasswordHash($this->password);
        }
        return parent::beforeSave($insert);
    }

    public function fields()
    {
        return [
            'id',
            'name',
            'email',
            'mobile_phone',
            'more_info' => function($model){
                $user_property = UserProperty::find()->where(['user_id' => $model->id, 'property_id' => $this->property_id])->one();
                if(!$user_property){
                    return [];
                }
                return [
                    'permission' => PermissionHelper::convertPermissionIdToName($user_property->permission_id),
                    'status' => $user_property->invited_token ? false : true
                ];
            },
            'created_at',
            'updated_at',
        ];
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return $this->authKey;
    }

    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne($token->getClaim('uid'));
    }

    public function getPermissionOfProperty()
    {

    }
}
