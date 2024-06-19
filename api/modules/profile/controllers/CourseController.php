<?php
/**
 * @author uluGbek <muhammadjonovulugbek98@gmail.com>
 * @link https://t.me/U_Muhammadjonov
 * @date 11-Apr-24, 21:25
 */

namespace api\modules\profile\controllers;

use api\controllers\ApiBaseController;
use api\models\User;
use api\modules\profile\models\UserLessonVideoSeason;
use api\modules\video\models\Course;
use api\modules\video\models\Video;
use api\modules\video\models\VideoSeason;
use api\modules\profile\models\UserHomework;
use common\modules\user\models\CourseDailyTask;
use api\modules\profile\models\CourseDailyTaskStatistic;
use common\modules\video\models\Homework;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\StaleObjectException;
use yii\web\NotFoundHttpException;

class CourseController extends ApiBaseController
{
    /**
     * @var string[]
     */
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    /**
     * @var bool
     */
    public $authRequired = true;

    /**
     * @return array
     */
    public function actionMyCourses()
    {
        $query = user()
            ->getEnrolledCourses();

        Course::setFields([
            'id',
            'name',
            'imageUrl',
            'description_1',
            'slug',
            'durationName',
            'activeSeasonsCount',
            'activePartsCount',
            'course_days',
            'completedFullLearnedLessonCount',
            'firstSeasonName' => function (Course $model) {
                return $model->firstSeason ? $model->firstSeason->name : '';
            },
            'firstPartName' => function (Course $model) {
                return $model->firstPart ? $model->firstPart->name : '';
            },
        ]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->success($dataProvider);
    }

    //<editor-fold desc="Module and Parts">

    /**
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionSeasons()
    {
        $key = Yii::$app->request->get('key');


        if (!$key) {
            throw new NotFoundHttpException('Kurs uchun modullar topilmadi!');
        }

        $course = Video::findActiveModelBySlug($key);

        if (!$course->isEnrolled) {
            return $this->error("Sizda ushbu kursni ko'rishga ruxsat mavjud emas yoki a'zolik muddati tugagan!");
        }

        if (!$course->getIsSerial()) {
            return $this->error('Ushbu key kurs uchun emas!');
        }

        $fisrtVideoSeason = $course->getSeasons()
            ->active()
            ->one();

        if ($fisrtVideoSeason && $fisrtVideoSeason->video_id == $course->id) {

            $course->setFirstSaveLearnedLessonAndUserSeason();

        }
        UserLessonVideoSeason::setFields([
            'id',
            'user_id',
            'parent_video_id',
            'copmleted_count',
            'lesson_count',
            'completed_percent',
        ]);


        VideoSeason::setFields([
            'id', 'name', 'slug',
            'activeVideoCount',
            'activePodcastCount',
            'userLessonVideoSeason'
        ]);

        return $this->success([
            'seasons' => $course->getSeasons()->active()->all(),
        ]);
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionSeasonParts()
    {
        $seasonName = Yii::$app->request->get('season');

        $model = VideoSeason::findActiveModelBySlug($seasonName);

        VideoSeason::setFields([
            'id',
            'name',
            'slug',
        ]);

        Video::setFields([
            'id',
            'slug',
            'name',
            'image' => 'imageUrl',
//            'sources' => 'mainStreamUrl',
            'part_description',
            'mediaDurationFormat',
            'activeHomeworks',
            'homeworkCompleteId',
            'homeworkCompleteName',
            'completeName',
            'completeId',
            'weekDays',
            'learnedLesson',
        ]);

        if (!$model->video->isEnrolled) {
            return $this->error("Sizda ushbu kursni ko'rishga ruxsat mavjud emas yoki a'zolik muddati tugagan!");
        }

        $query = Video::find()
            ->publishedDate()
            ->active()
            ->published()
            ->partial()
            ->andWhere(['season_id' => $model->id])
            ->orderBy(['sort_order' => SORT_ASC]);

        if (!$model->checkUserLessonVideoSeason()) {

            return $this->error("Sizda ushbu moduldan oldingi modullarni yakumlamagansiz!");
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->success([
            'season' => $model,
            'parts' => $dataProvider
        ]);

    }

    //</editor-fold>

    /**
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionPartDetail()
    {
        $partKey = Yii::$app->request->get('partKey');

        $part = Video::findActiveModelBySlug($partKey);

        if (!$part->parent->isEnrolled) {
            return $this->error("Sizda ushbu kursni ko'rishga ruxsat mavjud emas yoki a'zolik muddati tugagan!");
        }

        if (!$part->parent) {
            return $this->error("Ushbu api kurs uchun emas!");
        }

        if (!$part->checkAccessCourseDetail()) {
            return $this->error("Sizda ushbu darsdan oldingi darslarni ko'rmagansiz!");
        }

        Video::setFields([
            'id',
            'slug',
            'name',
            'image' => 'imageUrl',
//            'sources' => 'mainStreamUrl',
            'part_description',
            'activeHomeworks',
            'weekDays',
            'media_type_id',
            'mediaTypeName' => function (Video $model) {
                return $model->getMediaTypeName();
            },
            'audio_duration'
        ]);


        return $this->success($part);
    }

    /**
     * @return array
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws StaleObjectException
     */
    public function actionVideoPodcastWatch()
    {
        $partKey = Yii::$app->request->get('partKey');

        $part = Video::findActiveModelBySlug($partKey);

        if (!$part->parent->isEnrolled) {
            return $this->error("Sizda ushbu kursni ko'rishga ruxsat mavjud emas yoki a'zolik muddati tugagan!");
        }

        if (!$part->parent) {
            return $this->error("Ushbu api kurs uchun emas!");
        }

        if (!$part->checkAccessCourseDetail()) {
            return $this->error("Sizda ushbu darsdan oldingi darslarni ko'rmagansiz!");
        }

        Video::setFields([
            'id',
            'slug',
            'name',
            'imageUrl',
            'sources' => 'mainStreamUrl',
            'part_description',
//            'activeHomeworks',
//            'weekDays',
            'media_type_id',
            'mediaTypeName' => function (Video $model) {
                return $model->getMediaTypeName();
            },
            'audio_duration',
            'audioSources' => function (Video $model) {
                return $model->getAudioSource();
            }
        ]);

        $part->setLearnedLesson();

        return $this->success($part);
    }

    /**
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionUploadHomework()
    {
        $videoKey = Yii::$app->request->get('videoKey');
        $homeworkId = Yii::$app->request->get('homeworkId');

        $video = Video::findActiveModelBySlug($videoKey);

        if (!$video->parent) {
            return $this->error("Siz noto'g'ri videoga vazifa yuklayabsiz!");
        }

        $homework = Homework::find()
            ->andWhere(['video_id' => $video->id])
            ->andWhere(['id' => $homeworkId])
            ->active()
            ->one();

        if (!$homework) {
            return $this->error("Vazifa topilmadi");
        }

        $model = UserHomework::find()
            ->andWhere(['user_id' => user('id')])
            ->andWhere(['video_id' => $video->id])
            ->andWhere(['homework_id' => $homeworkId])
            ->one();

        if (!$model) {

            $model = new UserHomework([
                'user_id' => user('id'),
                'video_id' => $video->id,
                'homework_id' => $homeworkId,
                'status' => Homework::STATUS_INACTIVE,
            ]);

        } else {
            if ($model->isAccepted) {
                return $this->error("Tasdiqlangan vazifani yuklab bo'lmaydi!");
            }

            $model->status = Homework::STATUS_INACTIVE;
        }

        if ($model->load(Yii::$app->request->post(), '') && $model->validate()) {

            if ($model->file_url && !in_array($model->file_url->extension, UserHomework::$extensions)) {
                return $this->error("Quydagi fayllarga ruxsat berilgan: " . $model->getAllExtensionsName());
            }

            if ($model->save()) {
                return $this->success();
            }
        }

        return $this->error($model->firstErrorMessage);
    }

    /**
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionStatistic()
    {
        $key = Yii::$app->request->get('key');


        if (!$key) {
            throw new NotFoundHttpException('Kurs uchun modullar topilmadi!');
        }

        $course = Video::findActiveModelBySlug($key);

        if (!$course->isEnrolled) {
            return $this->error("Sizda ushbu kursni ko'rishga ruxsat mavjud emas yoki a'zolik muddati tugagan!");
        }

        $couseDailyTaskStatistic = CourseDailyTaskStatistic::find()
            ->andWhere(['course_id' => $course->id])
            ->andWhere(['user_id' => user('id')])
            ->one();

        if (!$couseDailyTaskStatistic) {

            $couseDailyTaskStatistic = new CourseDailyTaskStatistic([
                'course_id' => $course->id,
                'user_id' => user('id'),
                'did_you_run_percent' => 0,
                'did_you_read_percent' => 0,
                'did_you_meet_people_percent' => 0,
                'did_you_present_yourself_percent' => 0,
                'total_percent' => 0
            ]);

            $couseDailyTaskStatistic->save();
        }


        $couseDailyTasks = CourseDailyTask::find()
            ->andWhere(['course_id' => $course->id])
            ->andWhere(['user_id' => user('id')])
            ->orderBy(['day' => SORT_ASC])
            ->all();

        $data = [
            'dailyTasks' => $couseDailyTasks,
            'dailyTaskStatistic' => $couseDailyTaskStatistic,
        ];

        return $this->success($data);
    }

    /**
     * @return array|void
     * @throws NotFoundHttpException
     */
    public function actionSendDailyTask()
    {
        $key = Yii::$app->request->get('key');


        if (!$key) {
            throw new NotFoundHttpException('Kurs uchun modullar topilmadi!');
        }

        $course = Video::findActiveModelBySlug($key);

        if (!$course->isEnrolled) {
            return $this->error("Sizda ushbu kursni ko'rishga ruxsat mavjud emas yoki a'zolik muddati tugagan!");
        }

        $model = new CourseDailyTask([
            'course_id' => $course->id,
            'user_id' => user('id'),
            'date' => date('Y-m-d'),
        ]);

        if ($model->load(Yii::$app->request->post(), '') && $model->validate()) {

            $dailyTask = CourseDailyTask::find()
                ->andWhere(['course_id' => $course->id])
                ->andWhere(['user_id' => user('id')])
                ->andWhere(['day' => $model->day])
                ->one();

            $dateTask = CourseDailyTask::find()
                ->andWhere(['course_id' => $course->id])
                ->andWhere(['user_id' => user('id')])
                ->andWhere(['date' => date('Y-m-d')])
                ->one();

            if ($dailyTask) {

                if ($dailyTask->date == date('Y-m-d')) {
                    $dailyTask->did_you_run_1 = $model->did_you_run_1;
                    $dailyTask->did_you_read_2 = $model->did_you_read_2;
                    $dailyTask->did_you_meet_people_3 = $model->did_you_meet_people_3;
                    $dailyTask->did_you_present_yourself_4 = $model->did_you_present_yourself_4;
                    $dailyTask->update();
                    $model->calcStatistic();
                    return $this->success();
                } else {
                    return $this->error("Siz " . $model->day . ' kunga topshiriq bajaraib bo\'lgansiz!');
                }

            }

            if ($dateTask) {
                return $this->error("Siz ushbu sana uchun topshiriq bajarib bo'lgansiz!");
            }
            if (!$model->checkWriteAccessCourseDailyTask()) {
                return $this->error("Siz task yubora olmaysiz!");
            } else {
                if (!$model->save()) {

                    return $this->error($model->firstErrorMessage);
                }

                return $this->success();
            }
        }

        return $this->error("Ma'lumot saqlashda xatolik");
    }
}