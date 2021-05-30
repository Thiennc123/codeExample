<?php

namespace app\modules\api\services;

use Yii;
use app\modules\api\models\PrimaryObject;

class PrimaryObjectService
{
    public static function createOrUpdate($request, $edit_object = null)
    {
        $primary_object_attributes  = $request['primary_object'];

        if ($edit_object) {
            $primary_object = $edit_object;
        } else {
            $primary_object = new PrimaryObject();

            $data_area   = $primary_object_attributes['data_area'];
            $data_area[] = $primary_object_attributes['data_area'][0];
            $primary_object->data_area = $data_area;
        }

        $primary_object->attributes = $primary_object_attributes;

        $edit_object ? $primary_object->update() : $primary_object->save();

        return $primary_object;
    }
}
