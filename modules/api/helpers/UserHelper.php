<?php

namespace app\modules\api\helpers;

use Yii;
/*All Models are going to extend this class

  It has the behaviors method,which will be used for the created_at and updated_at

  Trying to emphasize DRY(Don't Repeat Yourself)  

*/

class UserHelper
{
    public static function generateToken($user_id){
        $jwt    = Yii::$app->jwt;
        $signer = $jwt->getSigner('HS256');
        $key    = $jwt->getKey();
        $time   = time();

        $token = $jwt->getBuilder()
            ->issuedBy('http://example.com') // Configures the issuer (iss claim)
            ->permittedFor('http://example.org') // Configures the audience (aud claim)
            ->identifiedBy('4f1g23a12aa', true) // Configures the id (jti claim), replicating as a header item
            ->issuedAt($time) // Configures the time that the token was issue (iat claim)
            ->withClaim('uid', $user_id) // Configures a new claim, called "uid"
            ->getToken($signer, $key); // Retrieves the generated token

        return $token;
    }  
}
