<?php

namespace app\modules\api\controllers\web;

use app\modules\api\controllers\DefaultController;
use app\modules\api\models\Permission;
use app\modules\api\models\UserProperty;
use app\modules\api\helpers\PermissionHelper;
use Yii;

class PermissionController extends DefaultController
{
    public function actionIndex()
    {
        $permissions = Permission::find()->all();
        return [
            'code'        => $this->response_code['request_success'],
            'permissions' => $permissions,
        ];
    }

    public function actionGetUserPermission()
    {
        $property_id   = Yii::$app->request->get('property_id');
        $current_user  = Yii::$app->user->identity;
        $permission_id = UserProperty::find()->where(['user_id' => $current_user->id, 'property_id' => $property_id, 'invited_token' => NULL])->select('permission_id')->scalar();

        return [
            'code'          => $this->response_code['request_success'],
            'permission' => PermissionHelper::convertPermissionIdToSlug($permission_id),
        ];
    }

}
