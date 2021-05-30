<?php

namespace app\modules\api\services;

use app\modules\api\helpers\CommonHelper;
use app\modules\api\models\PrimaryObject;
use app\modules\api\models\SecondaryObject;
use Yii;

class SecondaryObjectService
{
    public static function createOrUpdate($request, $edit_secondary_object = null)
    {
        $secondary_object_attributes   = $request['secondary_object'];
       
        if ($edit_secondary_object) {
            $secondary_object = $edit_secondary_object;
        } else {
            $secondary_object = new SecondaryObject();
        }

        if (isset($secondary_object_attributes['primary_object_id']) && $secondary_object_attributes['primary_object_id'] && !isset($secondary_object_attributes['point'])) {
            $primary_object = PrimaryObject::find()->where(['id' => $secondary_object_attributes['primary_object_id']])->one();
            if ($secondary_object->primary_object_id != $primary_object->id) {
                $secondary_object->point = [
                    'lat' => $primary_object->lat_centroid,
                    'lng' => $primary_object->lng_centroid,
                ];
            }

        } else {
            if(isset($secondary_object_attributes['point']))
              $secondary_object->point = $secondary_object_attributes['point'];            
        }

        $secondary_object->attributes = $secondary_object_attributes;

        $edit_secondary_object ? $secondary_object->update() : $secondary_object->save();

        return $secondary_object;
    }
}
