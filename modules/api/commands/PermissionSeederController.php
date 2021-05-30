<?php

namespace app\modules\api\commands;

use yii\console\Controller;
use yii\console\ExitCode;
use app\modules\api\models\Permission;

class PermissionSeederController extends Controller
{
    protected $permission_constant = [
        'owner' => 'Owner',
        'admin' => 'Admin',
        'modify' => 'Modify',
        'view' => 'View'
    ];

    public function actionIndex()
    {
        foreach ($this->permission_constant as $key => $value) {
            $permission = Permission::find()->where(['slug' => $key])->one();
            if(!$permission){
                $permission = new Permission();
                $permission->name = $value;
                $permission->slug = $key;
                $permission->save();
            }
        }

        return ExitCode::OK;
    }
}
