<?php

namespace app\modules\api\models;
use yii\db\Expression;
use Yii;

/**
 * This is the model class for table "mobs".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $type
 * @property string|null $location
 * @property int|null $primary_object_id
 * @property int|null $property_id
 * @property string|null $tag_colour
 * @property string|null $tag_number_range
 * @property string|null $date_of_birth
 * @property string|null $description
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class Mob extends \app\modules\api\models\AppModel
{
    public $point;
    public $lat;
    public $lng;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mobs';
    }

    public static function find()
    {
        return parent::find()->select(['mobs.*','ST_X(mobs.location) as lat', 'ST_Y(mobs.location) as lng']);
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
            [['location', 'description'], 'string'],
            [['primary_object_id', 'property_id'], 'integer'],
            [['date_of_birth', 'created_at', 'updated_at'], 'safe'],
            [['name', 'type', 'tag_colour', 'tag_number_range', 'breed'], 'string', 'max' => 255],
        ];
    }

    public function fields()
    {
        return [
            'id',
            'name',
            'type',
            'breed',
            'property_id',
            'primary_object_id',
            'lat',
            'lng',
            'tag_colour',
            'tag_number_range',
            'date_of_birth',
            'description',
            'created_at',
            'updated_at',
        ];
    }
}
