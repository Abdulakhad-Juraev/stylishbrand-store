<?php


namespace common\modules\video\traits;


use common\modules\video\models\Video;
use common\modules\video\models\VideoSeason;
use common\modules\video\query\VideoQuery;
use common\modules\video\query\VideoSeasonQuery;

/**
 *
 * Trait for Video model
 * @package common\modules\film\traits
 * @see Video
 *
 * @property Video $parent
 * @property Video[] $parts
 * @property-read VideoSeason $season
 * @property-read VideoSeason[] $seasons
 * @property int $partsCount
 * @property int $activePartsCount
 * @property int $activePodcastCount
 * @property int $activeVideoCount
 *
 */
trait VideoSerialPartsTrait
{

    /**
     * @var int
     */
    private $_partsCount;

    /**
     * @var int
     */
    private $_activePartsCount;

    /**
     * @var int
     */
    private $_seasonsCount;

    /**
     * @var int
     */
    private $_activeSeasonsCount;

    /**
     * @var int
     */
    private $_activeVideoCount;

    /**
     * @var int
     */
    private $_activePodcastCount;

    /**
     * Agar bu video serial ichida bo'lsa, bog'langan serialni qaytaradi
     * @return VideoQuery
     */
    public function getParent()
    {
        return $this->hasOne(Video::className(), ['id' => 'parent_id']);
    }

    /**
     * Serial ichidagi qismlari
     * @return VideoQuery
     */
    public function getParts()
    {
        return $this->hasMany(Video::className(), ['parent_id' => 'id']);
    }

    /**
     * Serial ichidagi fasllar
     * @return VideoSeasonQuery
     */
    public function getSeasons()
    {
        return $this->hasMany(VideoSeason::className(), ['video_id' => 'id'])->orderBy(['id' => SORT_ASC]);
    }

    /**
     * Serial qismi bog'langan faslni qaytaradi
     * @return VideoSeason
     */
    public function getSeason()
    {
        return $this->hasOne(VideoSeason::className(), ['id' => 'season_id']);
    }

    /**
     * @return int
     */
    public function getPartsCount()
    {
        if ($this->_partsCount === null) {
            $this->setPartsCount((int)$this->getParts()->count());
        }
        return $this->_partsCount;
    }

    /**
     * @param int $partsCount
     */
    public function setPartsCount($partsCount): void
    {
        $this->_partsCount = $partsCount;
    }

    /**
     * @return int
     */
    public function getActivePartsCount()
    {
        if ($this->_activePartsCount === null) {
            $this->setActivePartsCount((int)$this->getParts()->active()->count());
        }
        return $this->_activePartsCount;
    }

    /**
     * @param int $activePartsCount
     */
    public function setActivePartsCount($activePartsCount): void
    {
        $this->_activePartsCount = (int)$activePartsCount;
    }

    /**
     * @return mixed
     */
    public function getActiveSeasonsCount()
    {
        if ($this->_activeSeasonsCount === null) {
            $this->setActiveSeasonsCount((int)$this->getSeasons()->active()->count());
        }
        return $this->_activeSeasonsCount;
    }

    /**
     * @param mixed $seasonsCount
     */
    public function setActiveSeasonsCount($seasonsCount): void
    {
        $this->_activeSeasonsCount = $seasonsCount;
    }

    /**
     * @return mixed
     */
    public function getSeasonsCount()
    {
        if ($this->_seasonsCount === null) {
            $this->setSeasonsCount((int)$this->getSeasons()->count());
        }
        return $this->_seasonsCount;
    }

    /**
     * @param mixed $seasonsCount
     */
    public function setSeasonsCount($seasonsCount): void
    {
        $this->_seasonsCount = $seasonsCount;
    }

    /**
     * @return array
     */
    public function seasonsMap(): array
    {
        return map($this->seasons, 'id', 'name');
    }

    /**
     * @return mixed
     */
    public function getActivePodcastCount()
    {
        if ($this->_activePodcastCount === null) {

            $this->setActivePodcastCount((int)$this->getParts()->andWhere(['media_type_id' => Video::$media_type_id_audio])->active()->publishedDate()->count());
        }

        return $this->_activePodcastCount;
    }

    /**
     * @param mixed $activePodcastCount
     */
    public function setActivePodcastCount($activePodcastCount): void
    {
        $this->_activePodcastCount = $activePodcastCount;
    }

    /**
     * @return mixed
     */
    public function getActiveVideoCount()
    {
        if ($this->_activeVideoCount === null) {

            $this->setActiveVideoCount((int)$this->getParts()->andWhere(['media_type_id' => Video::$media_type_id_video])->active()->publishedDate()->count());
        }

        return $this->_activeVideoCount;
    }

    /**
     * @param mixed $activeVideoCount
     */
    public function setActiveVideoCount($activeVideoCount): void
    {
        $this->_activeVideoCount = $activeVideoCount;
    }

}
