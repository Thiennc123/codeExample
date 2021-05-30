<?php

namespace app\modules\api\services;

use app\modules\api\models\Property;
use app\modules\api\models\UserProperty;
use Yii;

/*All Models are going to extend this class

It has the behaviors method,which will be used for the created_at and updated_at

Trying to emphasize DRY(Don't Repeat Yourself)

 */

class PropertyService
{
    public static function getPropertiesForCurrentUser()
    {
        $current_user = Yii::$app->user->identity;

        $properties   = Property::find()
            ->innerJoin('users_properties', '`properties`.`id` = `users_properties`.`property_id`')
            ->where(['users_properties.user_id' => $current_user->id, 'invited_token' => NULL])
            ->orderBy(['properties.id' => SORT_DESC])
            ->all();

        return $properties;
    }
    public static function createOrUpdate($request, $editted_property = null)
    {
        $current_user = Yii::$app->user->identity;

        $property_attributes = $request['property'];
        if (!$editted_property) {
            $property_attributes['own_id'] = $current_user->id;
        }
        $property_attributes['updated_user'] = $current_user->id;

        if ($property_attributes['use_my_account'] == true) {
            $property_attributes['contact_name']  = $current_user->name;
            $property_attributes['contact_email'] = $current_user->email;
        }
        if ($editted_property) {
            $property = $editted_property;
        } else {
            $property = new Property();
        }

        $property->address_short_name = $request['addressShortName'];
        $property->point = $property_attributes['point'];

        $property->attributes = $property_attributes;

        $editted_property ? $property->update() : $property->save();

        return $property;
    }

    public static function createUserProperties($property, $permission_id, $user_id = null)
    {
        $user_property_attributes = [
            'user_id'       => $user_id ? $user_id : $property->own_id,
            'property_id'   => $property->id,
            'permission_id' => $permission_id,
            'invited_token' => $user_id ? Yii::$app->security->generateRandomString(32) : null,
        ];

        $user_property = UserProperty::find()->where(['user_id' => $user_id, 'property_id' => $property->id])->one();
        if (!$user_property) {
            $user_property = new UserProperty();
        }

        $user_property->attributes = $user_property_attributes;
        $user_property->save();

        return $user_property;
    }
}
