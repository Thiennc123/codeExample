<?php

namespace app\modules\api\models;
use yii\db\Expression;
use Yii;

/**
 * This is the model class for table "assets".
 *
 * @property int $id
 * @property string|null $name
 * @property int|null $property_id
 * @property int|null $secondary_object_id
 * @property string|null $location
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class Asset extends \app\modules\api\models\AppModel
{
    public $point;
    public $lat;
    public $lng;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'assets';
    }

    public static function find()
    {
        return parent::find()->select(['assets.*','ST_X(assets.location) as lat', 'ST_Y(assets.location) as lng']);
    }

    public function beforeSave($insert)
    {
        if($this->point){
            $this->location = new Expression("ST_GeometryFromText(:point)",
                array(':point'=>'POINT('.$this->point['lat'].' '.$this->point['lng'].')'));
        }

        return parent::beforeSave($insert);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['property_id', 'primary_object_id'], 'integer'],
            [['location', 'description'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'type', 'tag_colour', 'tag_number_range'], 'string', 'max' => 255],
        ];
    }

    public function fields()
    {
        return [
            'id',
            'name',
            'property_id',
            'primary_object_id',
            'type',
            'tag_colour',
            'tag_number_range',
            'date_of_birth',
            'description',
            'lat',
            'lng',
            'created_at',
            'updated_at',
        ];
    }

    public function getPrimaryObject()
    {
        return $this->hasOne(PrimaryObject::class, ['id' => 'primary_object_id']);
    }

}
