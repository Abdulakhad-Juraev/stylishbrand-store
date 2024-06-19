<?php
/**
 * @author uluGbek <muhammadjonovulugbek98@gmail.com>
 * @link https://t.me/U_Muhammadjonov
 * @date 13-Apr-24, 14:53
 */

namespace common\traits;

use common\modules\book\models\Book;
use common\modules\order\models\Order;
use soft\db\ActiveQuery;

trait UserBookOrderTrait
{
    /**
     * @return ActiveQuery|\yii\db\ActiveQuery
     */
    public function getBookPayedOrders()
    {
        return $this->hasMany(Order::class, ['user_id' => 'id'])
            ->andWhere(['type_id' => Order::$type_id_book])
            ->andWhere(['status' => Order::STATUS_ACTIVE])
            ->orderBy(['created_at' => SORT_DESC]);
    }

    /**
     * @return ActiveQuery|\yii\db\ActiveQuery
     */
    public function getPurchasedBooks()
    {
        return $this->hasMany(Book::class, ['id' => 'book_id'])->via('bookPayedOrders');
    }
}