<?php

namespace app\modules\api\services;

use app\modules\api\helpers\CommonHelper;
use app\modules\api\models\PrimaryObject;
use app\modules\api\models\Task;
use app\modules\api\models\Property;
use Yii;

class TaskService
{
    public static function createOrUpdate($request, $edit_task = null)
    {
        $current_user                  = Yii::$app->user->identity;
        $task_attributes               = $request['task'];
        $task_attributes['creator_id'] = $current_user->id;
        $task_attributes['due_date']   = isset($task_attributes['due_date']) ? CommonHelper::convertDateToDateTime($task_attributes['due_date']) : null;

        if ($edit_task) {
            $task = $edit_task;
        } else {
            $task = new Task();
        }


        if (isset($task_attributes['primary_object_id']) && ($task_attributes['primary_object_id'] ) && !isset($task_attributes['point'])) {
            $primary_object = PrimaryObject::find()->where(['id' => $task_attributes['primary_object_id']])->one();
            if ($primary_object && $task->primary_object_id != $primary_object->id) {
                $task->point = [
                    'lat' => $primary_object->lat_centroid,
                    'lng' => $primary_object->lng_centroid,
                ];
            }
        } else {
            if(isset($task_attributes['point']) && $task_attributes['point']){
              $task->point = $task_attributes['point'];

              $primary_object_id = Yii::$app->db->createCommand('
                  SELECT `id` FROM `primary_objects`
                  WHERE ST_CONTAINS(ST_GEOMFROMTEXT(ST_ASTEXT(`area`)), ST_GEOMFROMTEXT(\'POINT(' . $task_attributes['point']['lat'] . ' ' . $task_attributes['point']['lng'] . ')\'))
                  AND `property_id` = ' . $task_attributes['property_id'] . '
              ')->queryScalar();

              $task_attributes['primary_object_id'] = $primary_object_id;
            }else{
              //Create from Kaban board
              $property_id = $task_attributes['property_id'];
              $property        = Property::findOne($property_id);
              $task->location = $property ->location;
            }
        }

        $task->attributes = $task_attributes;

        $edit_task ? $task->update() : $task->save();
        return $task;
    }
}
