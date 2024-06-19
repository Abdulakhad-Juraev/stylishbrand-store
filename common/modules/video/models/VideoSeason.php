<?php

namespace common\modules\video\models;

use common\models\User;
use common\modules\video\behaviors\VideoSeasonSluggableBehavior;
use common\modules\video\query\VideoQuery;
use common\modules\video\query\VideoSeasonQuery;
use odilov\multilingual\behaviors\MultilingualBehavior;
use soft\db\ActiveRecord;
use soft\helpers\ArrayHelper;
use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "video_season".
 *
 * @property int $id
 * @property string $slug
 * @property string $name
 * @property string $description
 * @property int|null $video_id
 * @property int|null $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property User $createdBy
 * @property User $updatedBy
 * @property Video $video
 * @property UserLessonVideoSeason $userLessonVideoSeason
 */
class VideoSeason extends ActiveRecord
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
     * @var
     */
    private $_videoCount;

    /**
     * @var
     */
    private $_activeVideoCount;

    /**
     * @var
     */
    private $_podcastCount;

    /**
     * @var
     */
    private $_activePodcastCount;

    //<editor-fold desc="Parent" defaultstate="collapsed">

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'video_season';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['video_id', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['slug', 'name'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 2056],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
            [['video_id'], 'exist', 'skipOnError' => true, 'targetClass' => Video::className(), 'targetAttribute' => ['video_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'yii\behaviors\TimestampBehavior',
            'yii\behaviors\BlameableBehavior',
            'multilingual' => [
                'class' => MultilingualBehavior::class,
                'attributes' => ['name', 'description'],
            ],
            'slug' => [
                'class' => VideoSeasonSluggableBehavior::class,
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function labels()
    {
        return [
            'id' => 'ID',
            'name' => 'Nomi',
            'slug' => 'Slug',
            'film_id' => 'Kurs nomi',
            'activePartsCount' => 'Faol darslar soni',
            'partsCount' => 'Barcha darslar soni',
            'description' => 'Qisqa tavsif',
            'myVideoCount' => 'Videolar soni',
            'myPodcastCount' => 'Potkastlar soni',
        ];
    }

    /**
     * @return VideoSeasonQuery|\soft\db\ActiveQuery
     */
    public static function find()
    {
        $query = new VideoSeasonQuery(get_called_class());
        return $query->multilingual();
    }

    /**
     * @return mixed
     */
    public static function map($video_id)
    {
        return ArrayHelper::map(self::find()->andWhere(['video_id' => $video_id])->all(), 'id', 'name');
    }

    //</editor-fold>

    //<editor-fold desc="Relations" defaultstate="collapsed">

    /**
     * @return ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * @return ActiveQuery
     */
    public function getVideo()
    {
        return $this->hasOne(Video::className(), ['id' => 'video_id']);
    }

    /**
     * @return \soft\db\ActiveQuery
     */
    public function getParts()
    {
        return $this->hasMany(Video::class, ['season_id' => 'id']);
    }

    /**
     * @return \soft\db\ActiveQuery
     */
    public function getUserLessonVideoSeason()
    {
        return $this->hasOne(UserLessonVideoSeason::class, ['season_id' => 'id']);
    }

    //</editor-fold>

    /**
     * Fasl ichidagi qismlar soni
     * @return int
     */
    public function getPartsCount()
    {
        if ($this->_partsCount === null) {
            $this->setPartsCount($this->getParts()->count());
        }
        return $this->_partsCount;
    }

    /**
     * @param mixed $partsCount
     */
    public function setPartsCount($partsCount): void
    {
        $this->_partsCount = (int)$partsCount;
    }

    /**
     * Fasl ichidagi active qismlar soni
     * @return int
     */
    public function getActivePartsCount()
    {
        if ($this->_activePartsCount === null) {
            $this->setActivePartsCount($this->getParts()->active()->count());
        }
        return $this->_activePartsCount;
    }

    /**
     * @param mixed $activePartsCount
     */
    public function setActivePartsCount($activePartsCount): void
    {
        $this->_activePartsCount = (int)$activePartsCount;
    }

    /**
     * @return mixed
     */
    public function getVideoCount()
    {
        if ($this->_videoCount === null) {
            $this->setVideoCount($this->getParts()->andWhere(['media_type_id' => Video::$media_type_id_video])->count());
        }
        return $this->_videoCount;
    }

    /**
     * @param $_videoCount
     * @return void
     */
    public function setVideoCount($_videoCount): void
    {
        $this->_videoCount = (int)$_videoCount;
    }

    /**
     * @return mixed
     */
    public function getActiveVideoCount()
    {
        if ($this->_activeVideoCount === null) {
            $this->setActiveVideoCount($this->getParts()->active()->andWhere(['media_type_id' => Video::$media_type_id_video])->count());
        }
        return $this->_activeVideoCount;
    }

    /**
     * @param $_activeVideoCount
     * @return void
     */
    public function setActiveVideoCount($_activeVideoCount): void
    {
        $this->_activeVideoCount = (int)$_activeVideoCount;
    }

    /**
     * @return mixed
     */
    public function getPodcastCount()
    {
        if ($this->_podcastCount === null) {
            $this->setPodcastCount($this->getParts()->andWhere(['media_type_id' => Video::$media_type_id_audio])->count());
        }
        return $this->_podcastCount;
    }

    /**
     * @param $_podcastCount
     * @return void
     */
    public function setPodcastCount($_podcastCount): void
    {
        $this->_podcastCount = (int)$_podcastCount;
    }

    /**
     * @return mixed
     */
    public function getActivePodcastCount()
    {
        if ($this->_activePodcastCount === null) {
            $this->setActivePodcastCount($this->getParts()->active()->andWhere(['media_type_id' => Video::$media_type_id_audio])->count());
        }
        return $this->_activePodcastCount;
    }

    /**
     * @param $_activePodcastCount
     * @return void
     */
    public function setActivePodcastCount($_activePodcastCount): void
    {
        $this->_activePodcastCount = (int)$_activePodcastCount;
    }

    public function checkUserLessonVideoSeason()
    {
//        $fisrtVideoSeason = Video::find()->andWhere(['id' => $this->video_id])->getSeasons()
//            ->active()
//            ->one();

        $userLessonVideoSeason = UserLessonVideoSeason::find()
            ->andWhere(['user_id' => user('id')])
            ->andWhere(['season_id' => $this->id])
            ->one();

        if ($userLessonVideoSeason === null) {
            return false;
        }

        return true;
    }
}
