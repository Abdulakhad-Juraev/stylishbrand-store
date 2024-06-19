<?php
/**
 * @author uluGbek <muhammadjonovulugbek98@gmail.com>
 * @link https://t.me/U_Muhammadjonov
 * @date 28-Mar-24, 15:00
 */

namespace common\modules\video\columns;

use common\modules\video\models\Video;
use soft\grid\DataColumn;

class VideoStreamSrcColumn extends DataColumn
{
    public $attribute = 'stream_status_id';

    public $value = 'streamStatusName';

    public function init()
    {
        if ($this->filter === null) {
            $this->filter = Video::streamStatuses();
        }

        parent::init();
    }
}