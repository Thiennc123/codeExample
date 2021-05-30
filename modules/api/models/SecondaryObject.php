<?php

namespace app\modules\api\models;
use yii\db\Expression;
use Yii;

/**
 * This is the model class for table "secondary_objects".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $type
 * @property string|null $location
 * @property int|null $primary_object_id
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class SecondaryObject extends \app\modules\api\models\AppModel
{
    public $point;
    public $lat;
    public $lng;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'secondary_objects';
    }

    public static function find()
    {
        return parent::find()->select(['secondary_objects.*','ST_X(secondary_objects.location) as lat', 'ST_Y(secondary_objects.location) as lng']);
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
            [['location'], 'string'],
            [['property_id'], 'integer'],
            [['primary_object_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'type'], 'string', 'max' => 255],
        ];
    }

    public function fields()
    {
        return [
            'id',
            'name',
            'type',
            'property_id',
            'primary_object_id',
            'lat',
            'lng',
            'created_at',
            'updated_at',
        ];
    }
}
