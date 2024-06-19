<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace common\targets;

use common\services\TelegramService;
use Yii;
use yii\helpers\Json;
use yii\log\Target;

/**
 * FileTarget records log messages in a file.
 *
 * The log file is specified via [[logFile]]. If the size of the log file exceeds
 * [[maxFileSize]] (in kilo-bytes), a rotation will be performed, which renames
 * the current log file by suffixing the file name with '.1'. All existing log
 * files are moved backwards by one place, i.e., '.2' to '.3', '.1' to '.2', and so on.
 * The property [[maxLogFiles]] specifies how many history files to keep.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class TelegramTarget extends Target
{

    public $traceLevel = 3;

    public $logTrace = true;

    public $exludeTraceCodes = [404, 401, 403];

    public static $baseUrl = 'https://api.telegram.org/bot';
    public static $token = "5199963313:AAFw1iOEE0mRUxHDVHY26YQzvyJY2Z6zl7U:";
    public static $chat_id = "-1001661301201";

    /**
     * Sends log Message through the telegram
     * Starting from version 2.0.14, this method throws LogRuntimeException in case the log can not be exported.
     */
    public function export()
    {
        $statusText = Yii::$app->response->statusText;
        $statusCode = Yii::$app->response->statusCode;
        $errorMesage = Yii::$app->errorHandler->exception->getMessage();
        $errorClass = get_class(Yii::$app->errorHandler->exception);
        $allMsg = "$statusText ($statusCode)\n$errorClass\n\n<b>$errorMesage</b>";

        if ($this->logTrace) {
//            $trace = Yii::$app->errorHandler->exception->getTraceAsString();
            $trace = $this->renderTrace();
            $allMsg .= "\n\n" . $trace;
        }

        self::sendToTelegram($allMsg, ['error', 'error' . $statusCode]);
    }


    public static function sendToTelegram($msg, $tags = null)
    {
        $user = '';

        if (!is_console_app() && !Yii::$app->user->isGuest) {
            $user = '🤵 ' . Yii::$app->user->identity->fullname . PHP_EOL;
        }

        $url = is_console_app() ? '' : '🔗 ' . Yii::$app->request->absoluteUrl . PHP_EOL;

        if (is_array($msg) || is_object($msg)) {
            $msg = Json::encode($msg);
        }

        $msg = $user . $url . PHP_EOL . $msg;

        if ($tags) {
            $msg .= PHP_EOL . '#' . implode(' #', (array)$tags);
        }

        if (is_localhost()) {
            $msg .= PHP_EOL . '<i>Localhost</i>';
        }

        try {
            self::request([
                'chat_id' => self::$chat_id,
                'text' => $msg,
                'parse_mode' => 'HTML',
            ]);
        } catch (\Exception $e) {
            if (is_console_app()) {
                echo $e->getMessage();
            }
        }
    }

    protected static function request(array $data = []): void
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => self::$baseUrl . self::$token . '/sendMessage',
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

    public function renderTrace()
    {
        $traceMsg = '';

        if ($this->traceLevel > 0) {

            $statusCode = Yii::$app->response->statusCode;

            if (in_array($statusCode, $this->exludeTraceCodes)) {
                return '';
            }

            return Yii::$app->errorHandler->exception->getTraceAsString();

            /*     $traces = array_slice(Yii::$app->errorHandler->exception->getTrace(), 0, $this->traceLevel);
                 foreach ($traces as $i => $trace) {

                     $n = $i + 1;
                     $singleTrace = '#' . $n . ' ';
                     if (isset($trace['file'])) {
                         $singleTrace .= $trace['file'] . '(' . $trace['line'] . '): ';
                     }
                     if (isset($trace['class'])) {
                         $singleTrace .= $trace['class'];
                     }
                     if (isset($trace['type'])) {
                         $singleTrace .= $trace['type'];
                     }
                     if (isset($trace['function'])) {
                         $singleTrace .= $trace['function'] . '(';
                         if (isset($trace['args'])) {
                             $singleTrace .= implode(', ', $trace['args']);
                         }
                         $singleTrace .= ')';
                     }

                     $traceMsg .= $singleTrace . "\n";
                 }*/

        }
        return $traceMsg;

    }

}
