<?php
/**
 * @author uluGbek <muhammadjonovulugbek98@gmail.com>
 * @link https://t.me/U_Muhammadjonov
 * @date 28-Mar-24, 13:29
 */

namespace common\modules\video\query;

use soft\db\ActiveQuery;

class VideoSeasonQuery extends ActiveQuery
{
    /**
     * @return $this
     */
    public function withPartsCount()
    {
        $attribute = "(SELECT COUNT(*) from video WHERE video.season_id = video_season.id) as partsCount";
        if ($this->select === null) {
            $this->select('video_season.*');
        }
        $this->addSelect([$attribute]);
        return $this;
    }

    /**
     * @return $this
     */
    public function withActivePartsCount()
    {
        $attribute = "(SELECT COUNT(*) from video WHERE video.season_id = video_season.id AND  video.status=1) as activePartsCount";
        if ($this->select === null) {
            $this->select('video_season.*');
        }
        $this->addSelect([$attribute]);
        return $this;
    }
}