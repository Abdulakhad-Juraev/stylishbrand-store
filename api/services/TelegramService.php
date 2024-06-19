<?php

namespace api\services;

use Yii;
use yii\helpers\Json;

class TelegramService
{
    private static $instance = null;
    private $baseUrl = 'https://api.telegram.org/bot';

    public $token = "";
    public $chat_id = "";

    public static function getInstance(): TelegramService
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @param $msg mixed
     */
    public static function log($msg, $tags = null): void
    {
        self::getInstance()->sendMessage($msg, $tags);
    }

    public static function debug($msg = ''): void
    {
        self::getInstance()->sendMessage($msg);
    }

    /**
     * @param $msg mixed
     * @param null $method
     */
    public static function paynetLog($msg, $method = null): void
    {
        if (is_array($msg) || is_object($msg)) {
            $msg = Json::encode($msg);
        }
        if ($method) {
            $msg = $method . PHP_EOL . $msg;
        }
        $msg = '<b>💵Paynet</b>' . PHP_EOL . $msg;
        self::log($msg);
    }

    /**
     * @param mixed $msg
     * @param null|string|array $tags Hashtags
     */
    public function sendMessage($msg, $tags = null)
    {
        $date = "🗓 " . date('Y-m-d H:i:s') . PHP_EOL;
        $user = '';

        if (!is_console_app() && !Yii::$app->user->isGuest) {
            $user = '🤵 ' . Yii::$app->user->identity->fullname . PHP_EOL;
        }

//        $url = is_console_app() ? '' : '🔗 ' . Yii::$app->request->absoluteUrl . PHP_EOL;

        if (is_array($msg) || is_object($msg)) {
            $msg = Json::encode($msg);
        }

        $msg = $date . $user . PHP_EOL . $msg;

        if ($tags) {
            $msg .= PHP_EOL . '#' . implode(' #', (array)$tags);
        }

        if (is_localhost()) {
            $msg .= PHP_EOL . '<i>Localhost</i>';
        }

        $self = self::getInstance();

        try {
            $self->request([
                'chat_id' => $self->chat_id,
                'text' => $msg,
                'parse_mode' => 'HTML',
            ]);
        } catch (\Exception $e) {
            if (is_console_app()) {
                echo $e->getMessage();
            }
        }

    }


    private function request(array $data = []): void
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $this->baseUrl . $this->token . '/sendMessage',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 50,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_POSTFIELDS => http_build_query($data),
        ]);
        curl_exec($curl);
    }
}
