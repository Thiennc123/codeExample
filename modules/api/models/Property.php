<?php

namespace app\modules\api\models;

use app\modules\api\models\Permission;
use app\modules\api\models\User;
use app\modules\api\models\Task;
use app\modules\api\models\PrimaryObject;
use app\modules\api\services\PropertyService;
use app\modules\api\models\SecondaryObject;
use app\modules\api\models\Asset;
use app\modules\api\models\Mob;
use app\modules\api\models\Breed;
use Yii;
use yii\db\Expression;

/**
 * This is the model class for table "properties".
 *
 * @property int $id
 * @property int $own_id
 * @property string $name
 * @property string $address
 * @property string $country
 * @property string $state
 * @property string $postcode
 * @property string|null $type
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class Property extends \app\modules\api\models\AppModel
{
    public $address_short_name;
    public $point;
    public $lat;
    public $lng;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'properties';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['own_id', 'name', 'address', 'country', 'state'], 'required'],
            [['updated_user', 'own_id'], 'integer'],
            [['created_at', 'updated_at', 'location'], 'safe'],
            [['name', 'address', 'country', 'state', 'postcode', 'type', 'contact_name', 'contact_email'], 'string', 'max' => 255],
        ];
    }

    public static function find()
    {
        return parent::find()->select(['properties.*','ST_X(properties.location) as lat', 'ST_Y(properties.location) as lng']);
    }

    public function beforeSave($insert)
    {
        $dirty_attrs = $this->getDirtyAttributes();
        if ($dirty_attrs && (array_key_exists('country', $dirty_attrs) || array_key_exists('state', $dirty_attrs))) {
            $this->number = $this->address_short_name['country'] . '_' . $this->address_short_name['state'] . '_' . Yii::$app->security->generateRandomString(7);
        }
        $this->location = new Expression("ST_GeometryFromText(:point)",
            array(':point'=>'POINT('.$this->point['lat'].' '.$this->point['lng'].')'));

        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes)
    {
        if ($insert) {
            $permission_owner_id = Permission::find()->where(['slug' => 'owner'])->select('id')->scalar();
            PropertyService::createUserProperties($this, $permission_owner_id);
        }
        return parent::afterSave($insert, $changedAttributes);
    }

    public function featureProperties($field, $geometry){
        parent::featureProperties($field, $geometry);
        return [ 'id' => $this->getPrimaryKey() ];
    }

    public function fields()
    {
        return [
            'id',
            'number',
            'name',
            'address',
            'country',
            'state',
            'postcode',
            'owner' => function ($model) {
                $user = User::findOne($model->own_id);
                $user->property_id = $model->id;
                return $user;
            },
            'updated_user' => function ($model) {
                $user = User::findOne($model->own_id);
                $user->property_id = $model->id;
                return $user;
            },
            'lat',
            'lng',
            'contact_name',
            'contact_email',
            'created_at',
            'updated_at',
        ];
    }

    public function getUsers()
    {
        return $this->hasMany(User::class, ['id' => 'user_id'])->viaTable('users_properties', ['property_id' => 'id']);
    }

    public function getPrimaryObjects()
    {
        return $this->hasMany(PrimaryObject::class, ['property_id' => 'id']);
    }

    public function getTasks()
    {
        return $this->hasMany(Task::class, ['property_id' => 'id']);
    }

    public function getSecondaryObjects()
    {
        return $this->hasMany(SecondaryObject::class, ['property_id' => 'id']);
    }

    public function getAssets()
    {
        return $this->hasMany(Asset::class, ['property_id' => 'id']);
    }

    public function getMobs()
    {
        return $this->hasMany(Mob::class, ['property_id' => 'id']);
    }

    public function getBreeds()
    {
        return $this->hasMany(Breed::class, ['property_id' => 'id']);
    }
}
