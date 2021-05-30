<?php

namespace app\modules\api\models;

use Yii;

/**
 * This is the model class for table "user_properties".
 *
 * @property int $id
 * @property int $user_id
 * @property int $property_id
 * @property string|null $invited_token
 * @property int $permission_id
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class UserProperty extends \app\modules\api\models\AppModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users_properties';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'property_id', 'permission_id'], 'required'],
            [['user_id', 'property_id', 'permission_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['invited_token'], 'string', 'max' => 255],
        ];
    }
}
