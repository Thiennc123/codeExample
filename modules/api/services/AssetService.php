<?php

namespace app\modules\api\services;

use app\modules\api\helpers\CommonHelper;
use app\modules\api\models\PrimaryObject;
use app\modules\api\models\Asset;
use Yii;

class AssetService
{
    public static function createOrUpdate($request, $edit_asset = null)
    {
        $asset_attributes   = $request['asset'];

        if ($edit_asset) {
            $asset = $edit_asset;
        } else {
            $asset = new Asset();
        }

        if (isset($asset_attributes['primary_object_id']) && ($asset_attributes['primary_object_id'] || $asset_attributes['primary_object_id'] == 0) && !isset($asset_attributes['point']) && $asset_attributes['primary_object_id'] != 0) {
            $primary_object = PrimaryObject::find()->where(['id' => $asset_attributes['primary_object_id']])->one();
            if ($primary_object && $asset->primary_object_id != $primary_object->id) {
                $asset->point = [
                    'lat' => $primary_object->lat_centroid,
                    'lng' => $primary_object->lng_centroid,
                ];
            }
        } else {
            $asset->point = $asset_attributes['point'];

            $primary_object_id = Yii::$app->db->createCommand('
                SELECT `id` FROM `primary_objects`
                WHERE ST_CONTAINS(ST_GEOMFROMTEXT(ST_ASTEXT(`area`)), ST_GEOMFROMTEXT(\'POINT(' . $asset_attributes['point']['lat'] . ' ' . $asset_attributes['point']['lng'] . ')\'))
                AND `property_id` = ' . $asset_attributes['property_id'] . '
            ')->queryScalar();

            $asset_attributes['primary_object_id'] = $primary_object_id;
        }

        $asset->attributes = $asset_attributes;
        $edit_asset ? $asset->update() : $asset->save();
        
        return $asset;
    }
}
