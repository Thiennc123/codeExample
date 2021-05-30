<?php

namespace app\modules\api\services;

use app\modules\api\helpers\CommonHelper;
use app\modules\api\models\PrimaryObject;
use app\modules\api\models\Mob;
use Yii;

class MobService
{
    public static function createOrUpdate($request, $edit_mob = null)
    {
        $mob_attributes   = $request['mob'];
       
        if ($edit_mob) {
            $mob = $edit_mob;
        } else {
            $mob = new Mob();
        }

        if (isset($mob_attributes['primary_object_id']) && $mob_attributes['primary_object_id'] && !isset($mob_attributes['point'])) {
            $primary_object = PrimaryObject::find()->where(['id' => $mob_attributes['primary_object_id']])->one();
            if ($primary_object && $mob->primary_object_id != $primary_object->id) {
                $mob->point = [
                    'lat' => $primary_object->lat_centroid,
                    'lng' => $primary_object->lng_centroid,
                ];
            }

        } else {
                if(isset($mob_attributes['point']) && $mob_attributes['point']){
                    $mob->point = $mob_attributes['point'];

                    $primary_object_id = Yii::$app->db->createCommand('
                        SELECT `id` FROM `primary_objects`
                        WHERE ST_CONTAINS(ST_GEOMFROMTEXT(ST_ASTEXT(`area`)), ST_GEOMFROMTEXT(\'POINT(' . $mob_attributes['point']['lat'] . ' ' . $mob_attributes['point']['lng'] . ')\'))
                        AND `property_id` = ' . $mob_attributes['property_id'] . '
                    ')->queryScalar();
                    if($primary_object_id == false){
                        $primary_object_id = 0;
                    }
                    $mob_attributes['primary_object_id'] = $primary_object_id;
                }
            
        }

        $mob->attributes = $mob_attributes;

        $edit_mob ? $mob->update() : $mob->save();

        return $mob;
    }
}
