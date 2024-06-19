<?php
/*
 *  @author Shukurullo Odilov <shukurullo0321@gmail.com>
 *  @link telegram: https://t.me/yii2_dasturchi
 *  @date 19.05.2022, 11:37
 */

namespace common\filter;

use common\services\TelegramService;
use yii\helpers\Json;

class HttpBearerAuth extends \yii\filters\auth\HttpBearerAuth
{


    public $log = true;

    /**
     * {@inheritdoc}
     */
    public function authenticate($user, $request, $response)
    {
        $authHeader = $request->getHeaders()->get($this->header);
        $old = $authHeader;
        if ($authHeader !== null) {
            if ($this->pattern !== null) {
                if (preg_match($this->pattern, $authHeader, $matches)) {
                    $authHeader = $matches[1];
                } else {
                    $this->log("Token not found in header\n" . $authHeader . "\n" . $old);
                    return null;
                }
            }

            $identity = $user->loginByAccessToken($authHeader, get_class($this));
            if ($identity === null) {
                $this->challenge($response);
                $this->handleFailure($response);
            }

            return $identity;
        }

        return null;
    }

    private function log( $string)
    {
        if ($this->log) {
            TelegramService::log($string);
        }
    }

}
