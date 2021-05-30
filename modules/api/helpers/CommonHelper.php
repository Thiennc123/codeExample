<?php

namespace app\modules\api\helpers;

/*All Models are going to extend this class

It has the behaviors method,which will be used for the created_at and updated_at

Trying to emphasize DRY(Don't Repeat Yourself)

 */

class CommonHelper
{
    public static function convertDateToDateTime($date)
    {
        return date("Y-m-d H:i:s", strtotime($date));
    }
}
