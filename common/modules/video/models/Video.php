<?php

namespace common\modules\video\models;

use common\models\User;
use common\modules\user\models\CourseDailyTask;
use common\modules\user\models\CourseDailyTaskStatistic;
use common\modules\user\models\Enroll;
use common\modules\user\models\LearnedLesson;
use common\modules\video\behaviors\VideoBehavior;
use common\modules\video\behaviors\VideoSluggableBehavior;
use common\modules\video\query\VideoQuery;
use common\modules\video\traits\CheckUserLessonAndSeasonTrait;
use common\modules\video\traits\HomeWorkTrait;
use common\modules\video\traits\LearnedLessonStatusTrait;
use common\modules\video\traits\LearnedLessonTrait;
use common\modules\video\traits\OriginalPodcastTrait;
use common\modules\video\traits\OriginalVideoTrait;
use common\modules\video\traits\StreamTrait;
use common\modules\video\traits\VideoAccessTrait;
use common\modules\video\traits\VideoDaysWeekTrait;
use common\modules\video\traits\VideoImageTrait;
use common\modules\video\traits\VideoMediaSourceTrait;
use common\modules\video\traits\VideoMediaTypeTrait;
use common\modules\video\traits\VideoPriceTypeTrait;
use common\modules\video\traits\VideoSerialPartsTrait;
use common\modules\video\traits\VideoSerialTypeTrait;
use mohorev\file\UploadImageBehavior;
use odilov\multilingual\behaviors\MultilingualBehavior;
use soft\behaviors\TimestampConvertorBehavior;
use soft\db\ActiveRecord;
use soft\helpers\ArrayHelper;
use Yii;
use yii\db\ActiveQuery;
use yii\db\StaleObjectException;

/**
 * This is the model class for table "video".
 *
 * @property int $id
 * @property string|null $slug
 * @property string|null $fullName
 * @property string|null $name
 * @property string|null $description_1
 * @property string|null $description_2
 * @property string|null $image
 * @property int|null $sort_order
 * @property int|null $category_id
 * @property int|null $serial_type_id video/kurs
 * @property int|null $parent_id
 * @property int|null $season_id Fasl ya'ni bo'limlar
 * @property int|null $price_type_id Bepul/Pullik
 * @property string|null $org_src Original video
 * @property string|null $stream_src Stream video
 * @property string|null $representations
 * @property int|null $stream_status_id
 * @property string|null $stream_status_comment
 * @property int|null $stream_percentage
 * @property int|null $media_size
 * @property int|null $media_duration
 * @property int|null $has_org_src
 * @property int|null $has_streamed_src
 * @property int|null $queue_id
 * @property int|null $published_at
 * @property int|null $duration_number
 * @property string|null $duration_text
 * @property string|null $part_description
 * @property int|null $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $price
 * @property int|null $price_for_subscribers
 * @property int|null $isEnrolled
 * @property int|null $media_type_id
 * @property string|null $audio_org_src
 * @property int|null $audio_has_org_src
 * @property int|null $audio_media_size
 * @property string|null $audio_duration
 * @property int|null $isAudio
 * @property int|null $isVideo
 * @property int|null $course_days
 * @property int|null $in_process
 * @property int|null $is_free
 *
 *
 * @property VideoCategory $category
 * @property User $createdBy
 * @property Video $parent
 * @property Video[] $videos
 * @property User $updatedBy
 * @property Queue $queue
 * @property VideoOption $options
 * @property BeforeAfterCourse $beforeAfters
 * @property VideoAdditionalOption $videoAdditionalOptions
 * @property VideoComment $videoComment
 * @property VideoDaysWeek $weekDays
 * @property LearnedLesson $learnedLesson
 * @property CourseDailyTaskStatistic $courseDailyTaskStatistic
 * @property CourseDailyTask $courseDailyTasks
 */
class Video extends ActiveRecord
{
    use VideoAccessTrait;
    use OriginalVideoTrait;
    use VideoSerialTypeTrait;
    use VideoPriceTypeTrait;
    use VideoImageTrait;
    use StreamTrait;
    use VideoMediaSourceTrait;
    use VideoSerialPartsTrait;
    use LearnedLessonTrait;
    use LearnedLessonStatusTrait;
    use HomeWorkTrait;
    use VideoDaysWeekTrait;
    use VideoMediaTypeTrait;
    use OriginalPodcastTrait;
    use CheckUserLessonAndSeasonTrait;

    //<editor-fold desc="Constants" defaultstate="collapsed">

    const BASE_ORIGINAL_URL = '/uploads/media/orginal';
    const BASE_ORIGINAL_AUDIO_URL = '/uploads/course_audio/original';
//    const BASE_ORIGINAL_URL = '/uploads/media/disk2/orginal';
    const BASE_STREAM_URL = '/uploads/media/stream';
//    const BASE_STREAM_URL = '/uploads/media/disk2/stream';

    const SCENARIO_UPLOAD_VIDEO = 'uploadVideo';

    const SCENARIO_UPLOAD_AUDIO = 'uploadAudio';

    const SCENARIO_SERIAL_TYPE_SERIAL = 'serialTypeSerial';

    /**
     * Video yuklangandan keyin hal stream qilmasdan avval stream_statusi qiymati
     */
    const NO_STREAM = 3;

    /**
     * Video yuklangandan keyin hal stream qilmasdan avval stream_statusi qiymati
     */
    const IN_QUEUE = 4;

    /**
     * Video stream qilinayotgan paytdagi stream_status qiymati
     */
    const IS_STREAMING = 5;

    /**
     * Video stream tugagandan keyingi stream_status qiymati
     */
    const STREAM_FINISHED = 6;

    /**
     * Video stream qilishda xatolik yuz berdi
     */
    const STREAM_ERROR = 9;

    const REPRESENTATIONS = [720, 360];

    const DEFAULT_REPRESENTATION = 720;

    /**
     * Serial bo'lmagan oddiy kinolar
     */
    const SERIAL_TYPE_SINGLE = 1;

    /**
     * Seriallar
     */
    const SERIAL_TYPE_SERIAL = 2;

    /**
     * Serial ichidagi qismlar
     */
    const SERIAL_TYPE_PART = 3;

    const PRICE_TYPE_FREE = 1;
    const PRICE_TYPE_PREMIUM = 2;

    //</editor-fold>

    //<editor-fold desc="Parent" defaultstate="collapsed">

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'video';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'serial_type_id', 'published_at'], 'required'],
            [['name', 'duration_text', 'audio_duration', 'audio_org_src'], 'string', 'max' => 255],
            [['description_1', 'description_2', 'part_description'], 'string'],
            [['category_id', 'serial_type_id', 'season_id', 'price_type_id', 'status', 'sort_order',
                'duration_number', 'queue_id', 'price_for_subscribers', 'price',
                'audio_has_org_src', 'course_days', 'in_process', 'is_free'], 'integer'],
            [['category_id', 'serial_type_id', 'season_id', 'price_type_id', 'status', 'season_id',
                'audio_media_size', 'media_type_id'], 'integer'],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => VideoCategory::className(), 'targetAttribute' => ['category_id' => 'id']],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => Video::className(), 'targetAttribute' => ['parent_id' => 'id']],
            [['image'], 'image', 'maxSize' => 1024 * 1024 * 2],
            ['org_src', 'required', 'on' => self::SCENARIO_UPLOAD_VIDEO],
            ['audio_org_src', 'required', 'on' => self::SCENARIO_UPLOAD_AUDIO],
            ['org_src', 'file', 'mimeTypes' => 'video/*', 'on' => self::SCENARIO_UPLOAD_VIDEO, 'maxSize' => self::maxVideoSize()],
            ['audio_org_src', 'file', 'on' => self::SCENARIO_UPLOAD_AUDIO, 'maxSize' => self::maxVideoSize()],
            ['stream_status_id', 'default', 'value' => Video::NO_STREAM],
            ['price_type_id', 'in', 'range' => Video::priceTypeKeys()],
            ['stream_status_id', 'default', 'value' => Video::NO_STREAM],
            [['sort_order'], 'default', 'value' => 99999],
            [['published_at', 'week_days'], 'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'video' => [
                'class' => VideoBehavior::className(),
            ],
            'yii\behaviors\TimestampBehavior',
            'yii\behaviors\BlameableBehavior',
            'multilingual' => [
                'class' => MultilingualBehavior::class,
                'attributes' => ['name', 'description_1', 'description_2', 'part_description'],
            ],
            'slug' => [
                'class' => VideoSluggableBehavior::class,
            ],
            'image' => [
                'class' => UploadImageBehavior::class,
                'attribute' => 'image',
                'scenarios' => ['default'],
                'path' => '@frontend/web/uploads/images/video/{id}',
                'url' => '/uploads/images/video/{id}',
                'deleteOriginalFile' => true,
                'thumbs' => [
                    'preview' => ['width' => 1440],
                    'thumb' => ['width' => 300, 'quality' => 90],
                ],
            ],
            [
                'class' => TimestampConvertorBehavior::class,
                'attribute' => 'published_at'
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function labels()
    {
        return [
            'name' => 'Nomi',
            'slug' => 'Slug',
            'image' => 'Rasm',
            'sort_order' => 'Tartib raqami',
            'category_id' => 'Video kategoriyasi',
            'serial_type_id' => 'Video turi',
            'serialTypeName' => 'Video turi',
            'parent_id' => 'Kurs',
            'parent.name' => 'Kurs nomi',
            'season_id' => 'Modul',
            'season.name' => 'Modul nomi',
            'price_type_id' => 'Narx turi',
            'priceTypeName' => 'Narx turi',
            'priceTypeLabel' => 'Narx turi',
            'org_src' => 'Original video',
            'stream_src' => 'Stream video',
            'representations' => 'Representations',
            'stream_percentage' => 'Stream Foiz',
            'countInfo' => 'Raqamlar',
            'streamStatusName' => 'Video holati',
            'stream_status_id' => 'Video holati',
            'media_duration' => 'Davomiyligi',
            'media_size' => 'Hajmi',
            'has_org_src' => 'Original video bor',
            'partsCount' => 'Darslar soni',
            'activePartsCount' => 'Faol qismlar soni',
            'quality_id' => 'FHD',
            'qualityText' => 'Film sifati',
            'seasonsCount' => 'Modullar soni',
            'is_test' => 'Test uchunmi?',
            'published_at' => "E'lon qilinish sanasi",
            'duration_number' => "Kurga a'zo bo'lish davomiyligi qiymati",
            'duration_text' => "Kurga a'zo bo'lish davomiyligi",
            'description_1' => "Qisqa tavsif (top)",
            'description_2' => "Qisqa tavsif (footer)",
            'price_for_subscribers' => "Obuna bo'lganlar uchun narxi",
            'price' => "Narxi",
            'durationName' => "A'zolik davomiyligi",
            'part_description' => "Qisqa tavsif",
            'week_days' => 'Tavsiya qilingan kunlar',
            'badWeekDayAssign' => 'Tavsiya qilingan kunlar',
            'audio_duration' => 'Audio davomiyligi',
            'audio_media_size' => 'Audio o\'lchami',
            'media_type_id' => 'Fayl turi',
            'audio_org_src' => 'Audio Org Src',
            'course_days' => "Kurs necha kunni o'z ichiga oladi?",
            'in_process' => "Jarayondami ?",
            'is_free' => "Bepul sifatida belgilash",

        ];
    }

    /**
     * {@inheritdoc}
     * @return VideoQuery the active query used by this AR class.
     */
    public static function find()
    {
        $query = new VideoQuery(get_called_class());
        return $query->multilingual();
    }

    /**
     * @return array|array[]
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_UPLOAD_VIDEO] = [];
        $scenarios[self::SCENARIO_UPLOAD_AUDIO] = [];
        $scenarios[self::SCENARIO_SERIAL_TYPE_SERIAL] = [];
        return $scenarios;
    }
    //</editor-fold>


    //<editor-fold desc="Relations" defaultstate="collapsed">

    /**
     * @return ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(VideoCategory::className(), ['id' => 'category_id']);
    }

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
    public function getParent()
    {
        return $this->hasOne(Video::className(), ['id' => 'parent_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getVideos()
    {
        return $this->hasMany(Video::className(), ['parent_id' => 'id']);
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
    public function getQueue()
    {
        return $this->hasOne(Queue::class, ['id' => 'queue_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getOptions()
    {
        return $this->hasMany(VideoOption::className(), ['video_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getBeforeAfters()
    {
        return $this->hasMany(BeforeAfterCourse::className(), ['video_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getVideoAdditionalOptions()
    {
        return $this->hasMany(VideoAdditionalOption::className(), ['video_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getVideoComments()
    {
        return $this->hasMany(VideoComment::className(), ['video_id' => 'id'])->orderBy(['created_at' => SORT_DESC]);
    }

    /**
     * @return ActiveQuery
     */
    public function getLearnedLesson()
    {
        return $this->hasOne(LearnedLesson::className(), ['video_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getCourseDailyTaskStatistic()
    {
        return $this->hasOne(CourseDailyTaskStatistic::className(), ['course_id' => 'id']);
    }

    /**
     * @return \soft\db\ActiveQuery
     */
    public function getCourseDailyTasks()
    {

        return $this->hasMany(CourseDailyTask::className(), ['course_id' => 'id']);
    }
    //</editor-fold>

    //<editor-fold desc="Duration" defaultstate="collapsed">

    /**
     * @return string[]
     */
    public static function durationTexts()
    {
        return [
            'day' => 'kun',
            'month' => 'oy',
            'year' => 'yil',
        ];
    }

    /**
     * @return string
     */
    public function getDurationTextName()
    {
        return ArrayHelper::getArrayValue(self::durationTexts(), $this->duration_text, $this->duration_text);
    }

    /**
     * @return string
     */
    public function getDuration()
    {

        return $this->duration_number . ' ' . $this->duration_text;
    }

    /**
     * @return string
     */
    public function getDurationName()
    {
        return $this->duration_number . ' ' . $this->getDurationTextName();
    }

    //</editor-fold>

    /**
     * @return string
     */
    public function getFullName()
    {
        if ($this->getIsPartial()) {
            return $this->parent->name . '. ' . $this->name;
        }
        return $this->name;
    }

    /**
     * @return array
     */
    public static function courseMap()
    {
        return ArrayHelper::map(self::find()->nonPartial()
            ->andWhere(['serial_type_id' => self::SERIAL_TYPE_SERIAL])
            ->andWhere(['in_process' => false])
            ->active()
            ->localized()->all(), 'id', 'name');
    }

    /**
     * @return bool
     */
    public function getIsEnrolled(): bool
    {
        $enroll = Enroll::find()
            ->andWhere(['video_id' => $this->id])
            ->andWhere(['user_id' => user('id')])
            ->orderBy(['created_at' => SORT_DESC])
            ->one();

        if (!$enroll) {

            return false;
        }

        return (bool)!$enroll->isExpired;
    }

    /**
     * @return array
     */
    public static function map()
    {
        return ArrayHelper::map(self::find()->all(), 'id', 'name');
    }

    /**
     * @return array
     */
    public function attributeHints()
    {
        return [
            'audio_duration' => "Agar potkast yuklamoqchi bo'lsangiz. Namuna: 10:01 ko'rnishida to'ldirilishi kerak!",
            'sort_order' => "Tartib raqam qo'yishga e'tiborli bo'ling. Kurs sotib olganlar uchun ko'rish ketma-ketligi shunga bog'liq"
        ];
    }

    /**
     * @return bool
     */
    public function checkAccessCourseDetail()
    {
        $latestVideo = LearnedLesson::find()
            ->andWhere(['parent_id' => $this->parent_id])
            ->andWhere(['user_id' => user('id')])
            ->andWhere(['season_id' => $this->season_id])
            ->orderBy(['id' => SORT_DESC])
            ->one();

        if ($latestVideo) {

            return $latestVideo->video->sort_order >= ($this->sort_order - 1);
        }

        return false;
    }

}
