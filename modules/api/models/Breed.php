<?php

namespace app\modules\api\models;
use yii\db\Expression;
use Yii;

/**
 * This is the model class for table "breeds".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $slug
 * @property int|null $property_id
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class Breed extends \app\modules\api\models\AppModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'breeds';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['property_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'slug'], 'string', 'max' => 255],
        ];
    }

    public function fields()
    {
        return [
            'id',
            'name',
            'slug',
            'property_id',
            'created_at',
            'updated_at',
        ];
    }

}
