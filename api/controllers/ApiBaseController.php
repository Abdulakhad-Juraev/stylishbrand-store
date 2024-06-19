<?php

namespace api\controllers;

use common\filter\CorsFilter;
use common\filter\HttpBearerAuth;
use Yii;
use yii\rest\Controller;
use yii\web\ErrorAction;

/**
 * Site controller
 *
 * @property-read null|\common\models\User $user
 * @property-read bool $hasUser
 */
class ApiBaseController extends Controller
{

    public $enableCsrfValidation = false;

    public $authRequired = false;

    public $authExcept = [];

    public $authOnly;

    public $authOptional = [];

    public function actions(): array
    {
        return [
            'error' => [
                'class' => ErrorAction::class,
                'layout' => false,
            ]
        ];
    }

    public function beforeAction($action)
    {
        Yii::$app->response->headers->set('Access-Control-Allow-Origin', '*');
        return parent::beforeAction($action);
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        unset($behaviors['authenticator']);


        $behaviors['corsFilter'] = [
            'class' => CorsFilter::class,
            'cors' => [
                'Origin' => ['*'],
                'Access-Control-Allow-Origin' => ['*'],
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                'Access-Control-Request-Headers' => ['*'],
                'Access-Control-Allow-Credentials' => false,
                'Access-Control-Max-Age' => 86400,
                'Access-Control-Expose-Headers' => ['X-Pagination-Current-Page', 'X-Pagination-Page-Count', 'X-Pagination-Per-Page', 'X-Pagination-Total-Count'],
            ],
        ];

        if ($this->authRequired) {
            $behaviors['authenticator'] = [
                'class' => HttpBearerAuth::class,
                'except' => $this->authExcept,
                'only' => $this->authOnly,
                'optional' => $this->authOptional,
            ];
        }

        return $behaviors;
    }

    /**
     * @return bool
     */
    public function getHasUser(): bool
    {
        return !Yii::$app->user->isGuest;
    }

    /**
     * @return \common\models\User|null
     */
    public function getUser()
    {
        return Yii::$app->user->identity;
    }

    /**
     * @return yii\web\Request
     */
    public function request(): Yii\web\Request
    {
        return Yii::$app->request;
    }

    /**
     * @param null $name
     * @param null $defaultValue
     * @return array|mixed
     */
    public function post($name = null, $defaultValue = null)
    {
        return $this->request->post($name, $defaultValue);
    }

    /**
     * @param mixed $data
     * @param string $message
     * @return array
     */
    public function success($data = null, $message = 'success'): array
    {
        return [
            'status' => 200,
            'message' => $message,
            'data' => $data
        ];
    }

    /**
     * @param null|string $message
     * @param int $status
     * @return array
     */
    public function error($message = null, $status = 403): array
    {
        if ($message == null) {
            $message = "Xatolik yuz berdi!";
        }
        return [
            'status' => $status,
            'message' => $message,
        ];
    }

    /**
     * @return array
     */
    public function userNotFoundError()
    {
        return $this->error("Foydalanuvchi topilmadi!", 401);
    }

}
