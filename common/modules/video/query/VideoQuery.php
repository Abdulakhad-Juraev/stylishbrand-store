<?php
/**
 * @author uluGbek <muhammadjonovulugbek98@gmail.com>
 * @link https://t.me/U_Muhammadjonov
 * @date 28-Mar-24, 13:25
 */

namespace common\modules\video\query;

use common\modules\video\models\Video;
use soft\db\ActiveQuery;
use yii\db\ExpressionInterface;
use yii\db\Query;

class VideoQuery extends ActiveQuery
{
    /**
     * @return mixed
     */
    public function publishedDate()
    {
        return $this->andWhere(['<=', 'published_at', strtotime('now')]);
    }

    /**
     * {@inheritdoc}
     * @return Video|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Video|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * @return $this
     */
    public function published()
    {
        //todo filmni tomosha qilish uchun tayyorligini tekshirish kk
//        $this->andWhere(['has_streamed_src' => true]);
        return $this;
    }

    /**
     * Serial turidagi filmlarni ajratib olish
     * @param $isSerial bool
     * @return $this
     */
    public function serial(bool $isSerial = true)
    {
        $this->equal('serial_type_id', Video::SERIAL_TYPE_SERIAL, $isSerial);
        return $this;
    }

    /**
     * Bittalik kinolarni ajratib olish
     * @param $isSingle bool
     * @return $this
     */
    public function singleFilm(bool $isSingle = true)
    {
        $this->equal('serial_type_id', Video::SERIAL_TYPE_SINGLE, $isSingle);
        return $this;
    }

    /**
     * Serial ichidagi qismlarni ajratib olish
     * @param $isPartial bool
     * @return $this
     */
    public function partial(bool $isPartial = true)
    {
        $this->equal('serial_type_id', Video::SERIAL_TYPE_PART, $isPartial);
        return $this;
    }

    /**
     * Asosiy filmlarni (serial va kino) ajratib olish
     * @return $this
     */
    public function nonPartial()
    {
        $this->partial(false);
        return $this;
    }

//    /**
//     * @return $this
//     */
//    public function withLikesCount()
//    {
//        $attribute = "(SELECT COUNT(*) from like_dislike WHERE like_dislike.film_id = film.id AND like_dislike.type_id=" . LikeDislike::TYPE_LIKE . ") as likesCount";
//        $this->safeAddSelect($attribute);
//        return $this;
//    }
//
//    /**
//     * @return $this
//     */
//    public function withDislikesCount()
//    {
//        $attribute = "(SELECT COUNT(*) from like_dislike WHERE like_dislike.film_id = film.id AND like_dislike.type_id=" . LikeDislike::TYPE_DISLIKE . ") as dislikesCount";
//        $this->safeAddSelect($attribute);
//        return $this;
//    }

    /**
     * @return $this
     */
    public function withCommentsCount()
    {
        $attribute = "(SELECT COUNT(*) from video_comment WHERE video_comment.film_id = video.id) as commentsCount";
        $this->safeAddSelect($attribute);
        return $this;
    }

    /**
     * @return $this
     */
    public function withActiveCommentsCount()
    {
        $attribute = "(SELECT COUNT(*) from video_comment WHERE film_comment.film_id = film.id AND  video_comment.status=1) as activeCommentsCount";
        $this->safeAddSelect($attribute);
        return $this;
    }

//    /**
//     * @return $this
//     */
//    public function withLastSeensCount()
//    {
//        $attribute = "(SELECT COUNT(*) from last_seen_film WHERE last_seen_film.film_id = film.id) as lastSeensCount";
//        $this->safeAddSelect($attribute);
//        return $this;
//    }

    /**
     * @return $this
     * @todo nimagadir ishlamayapti
     */
    public function withPartsCount()
    {
        $queryString = (new Query())
            ->select('COUNT(*)')
            ->from('video as child_film')
//            ->where(['child_film.serial_type_id' => Film::SERIAL_TYPE_PART])
            ->andWhere(['child_film.parent_id' => 'film.id'])
            ->createCommand()
            ->getRawSql();
        $this->addSelectAs($queryString, 'partsCount');
        return $this;
    }

    /**
     * @return $this
     */
    public function withActivePartsCount()
    {
        $queryString = (new Query())
            ->select('COUNT(*)')
            ->from('video as child_film')
            ->where(['child_film.serial_type_id' => Video::SERIAL_TYPE_PART])
            ->andWhere(['child_film.parent_id' => 'film.id', 'child_film.status' => Video::STATUS_ACTIVE])
            ->createCommand()
            ->getRawSql();
        $this->addSelectAs($queryString, 'activePartsCount');
        return $this;
    }

    /**
     * @param $queryString string
     * @param string $asAttribute
     * @return $this
     */
    public function addSelectAs($queryString, string $asAttribute)
    {
        $column = "($queryString) as $asAttribute";
        $this->safeAddSelect($column);
        return $this;
    }

    /**
     * Safely adds column(s) to select.
     * @param string|array|ExpressionInterface $columns the columns to add to the select. See [[select()]] for more
     * details about the format of this parameter.
     * @return $this
     */
    public function safeAddSelect($columns)
    {
        if ($this->select === null) {
            $this->select('video.*');
        }
        $this->addSelect($columns);
        return $this;
    }
}