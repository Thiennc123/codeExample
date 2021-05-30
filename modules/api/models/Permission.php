<?php

namespace app\modules\api\models;

use Yii;

/**
 * This is the model class for table "permissions".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $slug
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class Permission extends \app\modules\api\models\AppModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'permissions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'slug'], 'string', 'max' => 255],
        ];
    }

    public function fields(){
        return [
            'id',
            'name',
            'slug'
        ];
    }
}
