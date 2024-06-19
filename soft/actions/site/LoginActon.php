<?php
/*
 * @author Shukurullo Odilov
 * @link telegram: https://t.me/yii2_dasturchi
 * @date 17.12.2021, 10:03
 */

namespace soft\actions\site;

use common\models\LoginForm;
use Yii;
use yii\base\Action;

class LoginActon extends Action
{

    /**
     * @var string
     */
    public $role;

    public $layout = '@soft/views/layouts/login';

    public $view = '@soft/views/site/login';

    public function run()
    {
        $controller = $this->controller;

        if (!Yii::$app->user->isGuest) {
            return $controller->goHome();
        }

        if ($this->layout) {
            $this->controller->layout = $this->layout;
        }

        $model = new LoginForm();

        if ($model->load(Yii::$app->request->post()) && $model->login()) {

            if ($this->role) {
                if (Yii::$app->user->can($this->role)) {
                    return $controller->goBack();
                } else {
                    Yii::$app->user->logout();
                }
            } else {
                return $controller->goBack();
            }
        }

        $model->password = '';

        return $controller->render($this->view, [
            'model' => $model,
        ]);
    }

}
