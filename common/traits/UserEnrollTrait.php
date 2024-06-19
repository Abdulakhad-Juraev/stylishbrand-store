<?php
/**
 * @author uluGbek <muhammadjonovulugbek98@gmail.com>
 * @link https://t.me/U_Muhammadjonov
 * @date 10-Apr-24, 20:36
 */

namespace common\traits;

use common\models\User;
use common\modules\order\models\Order;
use common\modules\user\models\Enroll;
use common\modules\userBalance\query\EnrollQuery;
use common\modules\video\models\Video;
use yii\db\ActiveQuery;

trait UserEnrollTrait
{
    /**
     * New enroll purchase
     * @param Video $course
     * @return bool
     */
    public function purchaseCourse(Video $course, Order $order = null, $payment_type = null)
    {
        $had_subscription = false;
        $realPrice = $order->price;

        if ($course->price != $order->price && $course->price_for_subscribers) {
            $realPrice = $course->price;
            $had_subscription = true;
        }

        $userEnroll = new Enroll([
            'user_id' => $order->user_id,
            'video_id' => $order->video_id,
            'sold_price' => $order->price,
            'real_price' => $realPrice,
            'end_at' => strtotime("+" . $course->getDuration(), today()),
            'had_subscription' => $had_subscription ? 1 : 0,
            'payment_type_id' => $order->payment_type_id,
            'order_id' => $order->id,
        ]);

        return $userEnroll->save();
    }

    /**
     * New enroll purchase
     * @param Video $course
     * @return bool
     */
    public function freePurchaseCourse(Video $course, User $user)
    {
        $userEnroll = new Enroll([
            'user_id' => $user->id,
            'video_id' => $course->id,
            'sold_price' => 0,
            'real_price' => $course->price,
            'end_at' => strtotime("+" . $course->getDuration(), today()),
            'had_subscription' => 0,
            'payment_type_id' => null,
            'order_id' => null,
        ]);

        return $userEnroll->save();
    }

    /**
     * @return EnrollQuery|ActiveQuery
     */
    public function getEnrolls()
    {
        return $this->hasMany(Enroll::class, ['user_id' => 'id']);
    }

    /**
     * @return \soft\db\ActiveQuery|ActiveQuery
     */
    public function getEnrolledCourses()
    {
        return $this->hasMany(Video::class, ['id' => 'video_id'])->via('enrolls');
    }
}