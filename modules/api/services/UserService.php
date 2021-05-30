<?php

namespace app\modules\api\services;

use app\modules\api\models\User;
use app\modules\api\models\UserProperty;

/*All Models are going to extend this class

It has the behaviors method,which will be used for the created_at and updated_at

Trying to emphasize DRY(Don't Repeat Yourself)

 */

class UserService
{
    public static function getUserByInvitedToken($token)
    {
        $user_id = UserProperty::find()->where(['invited_token' => $token])->select('user_id')->scalar();
        $user    = User::findOne($user_id);

        return $user;
    }

    public static function createOrUpdate($request, $edit_user = null)
    {
        $user_attributes  = $request['user'];

        if ($edit_user) {
            $user = $edit_user;
        } else {
            $user = new User();
        }

        $user->attributes = $user_attributes;

        $edit_user ? $user->update() : $user->save();

        return $user;
    }
}
