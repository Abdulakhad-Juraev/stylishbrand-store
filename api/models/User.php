<?php
/*
 *  @author Shukurullo Odilov <shukurullo0321@gmail.com>
 *  @link telegram: https://t.me/yii2_dasturchi
 *  @date 21.05.2022, 11:30...
 */

namespace api\models;

use api\modules\book\models\Book;
use api\modules\video\models\Course;
use api\modules\video\models\Video;
use common\modules\user\models\Enroll;
use common\modules\user\models\UserDevice;
use common\modules\userBalance\query\EnrollQuery;
use common\services\TelegramService;
use soft\db\ActiveQuery;
use Yii;
use yii\web\UploadedFile;

/**
 *
 * @property-read string $imageUrl
 */
class User extends \common\models\User
{

    public $log = true;

    /**
     * {@inheritdoc}
     * @return \common\models\User|null
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {

        if (!$token || $token == 'null') {
            return null;
        }

        $device = UserDevice::find()
            ->where(['token' => $token, 'status' => UserDevice::STATUS_ACTIVE])
            ->one();

        $userAgent = Yii::$app->request->getUserAgent();

        if (!$device) {
//            TelegramService::log("Device not found\n$token\nUserAgent: $userAgent");
            return null;
        }

        $user = static::findOne($device->user_id);

        if (!$user) {
            return null;
        }

        if ($user->status != User::STATUS_ACTIVE) {
            return null;
        }

        return $user;
    }

    /**
     * @param $message
     * @return void
     */
    public function log($message)
    {
        if ($this->log) {
            TelegramService::log($message);
        }
    }


    /**
     * @return string[]
     */
    public function fields()
    {
        return [
            'id',
            'username',
            'firstname',
            'lastname',
            'status',
            'statusName' => function (User $model) {
                return $this->statusName;
            },
            'auth_key',
            'imageUrl' => function (User $model) {
                return $model->getImageUrl();
            },
            'allowed_devices_count' => function (User $model) {
                return $model->allowedActiveDevicesCount;
            },
            'activeTariff' => function (User $model) {
                if ($model->lastActiveUserTariff) {
                    return $model->lastActiveUserTariff->tariff->name;

                }
                return t('Free');
            },
            'activeTariffId' => function (User $model) {
                if ($model->lastActiveUserTariff) {
                    return $model->lastActiveUserTariff->id;

                }
                return null;
            },
            'expiredAt' => function (User $model) {
                return $model->lastActiveUserTariff ? date('d.m.Y', $model->lastActiveUserTariff->expired_at) : '';
            },
        ];
    }

    /**
     * @return string
     */
    public function getImageUrl()
    {
        return $this->image ? Yii::$app->urlManager->hostInfo . '/uploads/user/' . $this->image : Yii::$app->urlManager->hostInfo . '/template/img/Avatar.png';
    }

    /**
     * @return EnrollQuery|ActiveQuery|\yii\db\ActiveQuery
     */
    public function getEnrolls()
    {
        return $this->hasMany(Enroll::class, ['user_id' => 'id']);
    }

    /**
     * @return ActiveQuery|\yii\db\ActiveQuery
     */
    public function getEnrolledCourses()
    {
        return $this->hasMany(Course::class, ['id' => 'video_id'])->via('enrolls');
    }

    /**
     * @return ActiveQuery|\yii\db\ActiveQuery
     */
    public function getPurchasedBooks()
    {
        return $this->hasMany(Book::class, ['id' => 'book_id'])->via('bookPayedOrders');
    }

    /**
     * @return ActiveQuery|\yii\db\ActiveQuery
     */
    public function getUsedPromoCodeBooks()
    {
        return $this->hasMany(Book::class, ['id' => 'book_id'])->via('promoCodeBooks');
    }

}
