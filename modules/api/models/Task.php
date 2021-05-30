<?php

namespace app\modules\api\models;

use Yii;
use yii\db\Expression;
use app\modules\api\models\User;
use app\modules\api\models\PrimaryObject;

/**
 * This is the model class for table "tasks".
 *
 * @property int $id
 * @property string $title
 * @property string|null $details
 * @property string $priority
 * @property string|null $due_date
 * @property string $status
 * @property int|null $creator_id
 * @property int|null $assignee_id
 * @property int|null $primary_object_id
 * @property string|null $location
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class Task extends \app\modules\api\models\AppModel
{
    public $point;
    public $lat;
    public $lng;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tasks';
    }

    public static function find()
    {
        return parent::find()->select(['tasks.*','ST_X(tasks.location) as lat', 'ST_Y(tasks.location) as lng']);
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
            [['title', 'priority', 'status'], 'required'],
            [['details', 'location'], 'string'],
            [['due_date', 'created_at', 'updated_at', 'primary_object_id'], 'safe'],
            [['creator_id', 'assignee_id', 'property_id'], 'integer'],
            [['title', 'priority', 'status'], 'string', 'max' => 255],
        ];
    }

    public function fields()
    {
        return [
            'id',
            'title',
            'priority',
            'status',
            'due_date',
            'property_id',
            'primary_object_id',
            'creator' => function ($model) {
                $user = User::findOne($model->creator_id);
                $user->property_id = $model->property_id;
                return $user;
            },
            'assignee' => function ($model) {
                if($model->assignee_id == 0){
                    return [
                        'id' => 0,
                        'name' => 'Anonymous'
                    ];
                }
                $user = User::findOne($model->assignee_id);
                $user->property_id = $model->property_id;
                return $user;
            },
            'lat',
            'lng',
            'details',
            'created_at',
            'updated_at',
        ];
    }
}
