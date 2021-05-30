<?php

namespace app\modules\api\helpers;

use Yii;
use app\modules\api\models\Permission;
/*All Models are going to extend this class

  It has the behaviors method,which will be used for the created_at and updated_at

  Trying to emphasize DRY(Don't Repeat Yourself)  

*/

class PermissionHelper
{
    public static function convertPermissionIdToName($id){
        return Permission::find()->where(['id' => $id])->select('name')->scalar();
    }

    public static function convertPermissionIdToSlug($id){
        return Permission::find()->where(['id' => $id])->select('slug')->scalar();
    }

    public static function convertSlugToPermissionId($slug){
        return Permission::find()->where(['slug' => $slug])->select('id')->scalar();
    }
}
