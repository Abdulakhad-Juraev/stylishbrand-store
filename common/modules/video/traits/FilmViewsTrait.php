<?php
/*
 *  @author Shukurullo Odilov <shukurullo0321@gmail.com>
 *  @link telegram: https://t.me/yii2_dasturchi
 *  @date 13.05.2022, 11:32
 */

namespace common\modules\film\traits;

/**
 *
 * Trait for Film model
 * @package common\modules\film\traits
 * @see \common\modules\film\models\Film
 *
 * @property int $viewsCount
 */
trait FilmViewsTrait
{

    /**
     * @var int Views Count
     *
     */
    private $_viewsCount;

    /**
     * @return int view count
     * @todo How to count views?
     */
    public function getViewsCount() :int
    {
        if ($this->_viewsCount === null) {
            $this->setViewsCount($this->getLastSeensCount());
        }
        return $this->_viewsCount;
    }

    /**
     * @param int $viewsCount
     */
    public function setViewsCount(int $viewsCount): void
    {
        $this->_viewsCount = $viewsCount;
    }

}
