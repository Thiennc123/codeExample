<?php
namespace app\modules\api\notifications;

use yii\base\BaseObject;
use Yii;
use app\modules\api\models\UserProperty;

class AcceptInviteEmail extends BaseObject implements \yii\queue\JobInterface
{
    public $base_url;
    public $user;
    public $property;
    public $isCreatedUser;
    public $invited_token;

    public function execute($queue)
    {
        print_r("Queue processing \n");

        $url = $this->base_url . '/verify?token=' . $this->invited_token . '&property_id=' . $this->property['id'];

        if($this->isCreatedUser){
            $url = $this->base_url . '/signup?token=' . $invited_token;
        }

        Yii::$app->mailer->compose('@app/modules/api/views/mail-templates/accept-invite', 
            [
                'user' => $this->user['name'],
                'property' => $this->property['name'],
                'url'  => $url
            ])
            ->setFrom('from@domain.com')
            ->setTo($this->user['email'])
            ->setSubject('Email sent from AirAgri')
            ->send();

        print_r("Queue done");
    }
}
