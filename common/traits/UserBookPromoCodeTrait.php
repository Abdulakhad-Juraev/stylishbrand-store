<?php
/**
 * @author uluGbek <muhammadjonovulugbek98@gmail.com>
 * @link https://t.me/U_Muhammadjonov
 * @date 13-Apr-24, 20:52
 */

namespace common\traits;

use common\modules\book\models\Book;
use common\modules\book\models\BookPromoCode;
use common\modules\order\models\Order;
use soft\db\ActiveQuery;

trait UserBookPromoCodeTrait
{
    /**
     * @return ActiveQuery|\yii\db\ActiveQuery
     */
    public function getPromoCodeBooks()
    {
        return $this->hasMany(BookPromoCode::class, ['user_id' => 'id'])
            ->andWhere(['is_use' => true])
            ->orderBy(['updated_at' => SORT_DESC]);
    }

    /**
     * @return ActiveQuery|\yii\db\ActiveQuery
     */
    public function getUsedPromoCodeBooks()
    {
        return $this->hasMany(Book::class, ['id' => 'book_id'])->via('promoCodeBooks');
    }
}