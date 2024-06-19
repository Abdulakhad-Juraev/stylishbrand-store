<?php

/*
 *  @author Shukurullo Odilov <shukurullo0321@gmail.com>
 *  @link telegram: https://t.me/yii2_dasturchi
 *  @date 13.05.2022, 14:54
 */

namespace api\behaviors;

use Yii;
use yii\base\Application;

/**
 * Language setter for API requests.
 * @package api\behaviors
 */
class ChangeLanguageBehavior extends \soft\i18n\ChangeLanguageBehavior
{

    /**
     * @var
     */
    public $language;

    /**
     * @return string[]
     */
    public function events()
    {
        return [
            Application::EVENT_BEFORE_REQUEST => 'beforeRequest',
        ];
    }

    /**
     * @return void
     */
    public function beforeRequest()
    {
        Yii::$app->language = $this->setApplicationLanguage();
    }

    /**
     * @return mixed|string|void
     */
    private function setApplicationLanguage()
    {
        $params = Yii::$app->params;
        $lang = $this->getRequestLanguage();

        if (!array_key_exists($lang, $params['languages'])) {
            $lang = $params['defaultLanguage'];
        }
        return $lang;
    }

    /**
     * @return string
     */
    protected function getRequestLanguage()
    {
        return Yii::$app->request->headers->get('Accept-Language', Yii::$app->params['defaultLanguage']);
    }

}
