<?php

namespace app\modules\api\models;

use Yii;
use yii\db\Expression;
use app\modules\api\models\Property;
use app\modules\api\models\Asset;

/**
 * This is the model class for table "primary_objects".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $type
 * @property string|null $area
 * @property string|null $acreage
 * @property int $property_id
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class PrimaryObject extends \app\modules\api\models\AppModel
{
    public $coordinates;
    public $data_area;
    public $lat_centroid;
    public $lng_centroid;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'primary_objects';
    }

    public static function find()
    {
        return parent::find()->select([
            'primary_objects.*',
            'ST_AsText(primary_objects.area) as coordinates',
            'ST_X(ST_Centroid(primary_objects.area)) as lat_centroid',
            'ST_Y(ST_Centroid(primary_objects.area)) as lng_centroid'
        ]);
    }

    public function beforeSave($insert)
    {
        if($this->data_area){
            $res = array_map(function($data){
                return str_replace(", ", " ", $data);
            }, $this->data_area);

            $this->area = new Expression("ST_GeometryFromText(:polygon)",
                array(':polygon'=>'POLYGON(('.implode(", ", $res).'))'));
        }

        return parent::beforeSave($insert);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['area', 'description'], 'string'],
            [['property_id'], 'required'],
            [['property_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'type', 'color'], 'string', 'max' => 255],
            [['acreage'], 'number'],
        ];
    }

    public function fields()
    {
        return [
            'id',
            'name',
            'area' => function($model){
                $polygon = substr($model->coordinates, 9, -2);
                $result = array_map(function($res){
                    return explode(" ", $res);
                }, explode(",", $polygon));
                return $result;
            },
            'lat_centroid',
            'lng_centroid',
            'acreage',
            'property_id',
            'color',
            'type',
            'description',
            'created_at',
            'updated_at',
            'assets'
        ];
    }

    public function getProperty()
    {
        return $this->hasOne(Property::class, ['id' => 'property_id']);
    }

    public function getAssets()
    {
        return $this->hasMany(Asset::class, ['primary_object_id' => 'id']);
    }
}
