<?php
namespace app\modules\api\notifications;

use yii\base\BaseObject;
use Yii;

class VerifyApiEmail extends BaseObject implements \yii\queue\JobInterface
{
    public $base_url;
    public $user;

    public function execute($queue)
    {
        print_r("Queue processing \n");

        Yii::$app->mailer->compose('@app/modules/api/views/mail-templates/register', 
            [
                'user' => $this->user['name'],
                'url'  => $this->base_url . '/verify?token=' . $this->user['confirmation_token'],
            ])
            ->setFrom('from@domain.com')
            ->setTo($this->user['email'])
            ->setSubject('Email sent from AirAgri')
            ->send();

        print_r("Queue done");
    }
}
