<?php
/*
 *  @author Shukurullo Odilov <shukurullo0321@gmail.com>
 *  @link telegram: https://t.me/yii2_dasturchi
 *  @date 13.05.2022, 15:21
 */

/*
 *  @author Shukurullo Odilov <shukurullo0321@gmail.com>
 *  @link telegram: https://t.me/yii2_dasturchi
 *  @date 13.05.2022, 14:56
 */

namespace soft\i18n;

use Yii;
use yii\base\Application;

/**
 *
 * Set language for application
 *
 * @property-read string $requestLanguage
 */
class ChangeLanguageBehavior extends \yii\base\Behavior
{
    public $language;

    public function events()
    {
        return [
            Application::EVENT_BEFORE_REQUEST => 'beforeRequest',
        ];
    }

    public function beforeRequest()
    {
        $this->setApplicationLanguage();
    }

    private function setApplicationLanguage()
    {
        $params = Yii::$app->params;
        $lang = $this->getRequestLanguage();
        if (!array_key_exists($lang, $params['languages'])) {
            $lang = $params['defaultLanguage'];
        }
        Yii::$app->language = $lang;
    }

    protected function getRequestLanguage()
    {
        $params = Yii::$app->params;
        return Yii::$app->request->get($params['languageParam'], $params['defaultLanguage']);
    }

}
