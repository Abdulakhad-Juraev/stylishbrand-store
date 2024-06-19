<?php
/**
 * @author uluGbek <muhammadjonovulugbek98@gmail.com>
 * @link https://t.me/U_Muhammadjonov
 * @date 12-Apr-24, 16:03
 */

namespace common\modules\video\traits;

use common\modules\user\models\Enroll;
use common\modules\user\models\LearnedLesson;
use common\modules\video\models\UserLessonVideoSeason;
use common\modules\video\models\Video;
use common\modules\video\models\VideoComment;
use common\modules\video\models\VideoSeason;
use soft\db\ActiveQuery;
use yii\db\StaleObjectException;

/**
 * @property LearnedLesson $learnedLessons
 * @property int $completedFullLearnedLessonCount
 */
trait LearnedLessonTrait
{


    /**
     * @return bool|int
     * @throws \Throwable
     * @throws StaleObjectException
     */
    public function setLearnedLesson()
    {
        $learnedLesson = LearnedLesson::find()
            ->andWhere(['video_id' => $this->id])
            ->andWhere(['season_id' => $this->season_id])
            ->andWhere(['parent_id' => $this->parent->id])
            ->andWhere(['user_id' => user('id')])
            ->one();

        $enroll = Enroll::find()
            ->andWhere(['user_id' => user('id')])
            ->andWhere(['video_id' => $this->parent->id])
            ->started()->nonExpired()
            ->one();

        if ($enroll) {
            $enroll->last_video_id = $this->id;
            $enroll->update();
        }

        if (!$learnedLesson) {

            $learnedLessonModel = new LearnedLesson([
                'video_id' => $this->id,
                'user_id' => user('id'),
                'is_completed' => LearnedLesson::$is_completed_full,
                'parent_id' => $this->parent->id,
                'season_id' => $this->season_id,
            ]);

            return $learnedLessonModel->save();
        } else {
            $learnedLesson->season_id = $this->season_id;
            $learnedLesson->is_completed = LearnedLesson::$is_completed_full;
        }

        $learnedLesson->parent_id = $this->parent_id;
        $learnedLesson->save();

        $this->currentActiveLearnedLesson($this->season);


        $userLessonVideoSeason = UserLessonVideoSeason::find()
            ->andWhere(['parent_video_id' => $this->parent_id])
            ->andWhere(['user_id' => user('id')])
            ->andWhere(['season_id' => $this->season_id])
            ->one();

        $completedLearnedLessonCount = LearnedLesson::find()
            ->andWhere(['user_id' => user('id')])
            ->andWhere(['season_id' => $this->season_id])
            ->andWhere(['parent_id' => $this->parent->id])
            ->andWhere(['is_completed' => LearnedLesson::$is_completed_full])
            ->count();

        $currentSeasonActivePartsCount = $this->season->getActivePartsCount();

        if (!$currentSeasonActivePartsCount) {

            $currentSeasonActivePartsCount = 1;
        }

        if (!$userLessonVideoSeason) {

            $userLessonVideoSeasonModel = new UserLessonVideoSeason([
                'parent_video_id' => $this->parent_id,
                'user_id' => user('id'),
                'season_id' => $this->season->id,
                'copmleted_count' => $completedLearnedLessonCount,
                'lesson_count' => $currentSeasonActivePartsCount,
                'completed_percent' => ($completedLearnedLessonCount / $currentSeasonActivePartsCount) * 100
            ]);

            $userLessonVideoSeasonModel->save();
        } else {

            $userLessonVideoSeason->copmleted_count = $completedLearnedLessonCount;
            $userLessonVideoSeason->lesson_count = $currentSeasonActivePartsCount;
            $userLessonVideoSeason->completed_percent = ($completedLearnedLessonCount / $currentSeasonActivePartsCount) * 100;
            $userLessonVideoSeason->save();

            if ($completedLearnedLessonCount == $this->season->getActivePartsCount()) {
                $this->nextSeasonActive($this->season);
            }
        }
    }

    /**
     * @return ActiveQuery
     */
    public function getLearnedLessons()
    {
        return $this->hasMany(LearnedLesson::className(), ['parent_id' => 'id']);
    }

    /**
     * @return bool|int|string|null
     */
    public function getCompletedFullLearnedLessonCount()
    {
        return (int)$this->getLearnedLessons()->andWhere(['user_id' => user('id')])->andWhere(['is_completed' => self::$is_completed_full])->count();
    }

    /**
     * User birinchi seasonni yakunlasa keyingi seasonni user uchun aktiv qilish
     * @param VideoSeason $currentSeason
     * @return void
     */
    public function nextSeasonActive(VideoSeason $currentSeason)
    {
        $nextSeason = VideoSeason::find()
            ->andWhere('video_id')
            ->andWhere(['>', 'id', $currentSeason->id])
            ->one();

        if ($nextSeason) {

            $userLessonVideoSeason = UserLessonVideoSeason::find()
                ->andWhere(['parent_video_id' => $this->parent_id])
                ->andWhere(['user_id' => user('id')])
                ->andWhere(['season_id' => $nextSeason->id])
                ->one();

            $completedLearnedLessonCount = LearnedLesson::find()
                ->andWhere(['parent_id' => $this->parent->id])
                ->andWhere(['season_id' => $nextSeason->id])
                ->andWhere(['user_id' => user('id')])
                ->andWhere(['is_completed' => LearnedLesson::$is_completed_full])
                ->count();

            $nextSeasonActivePartsCount = $nextSeason->getActivePartsCount();

            if (!$nextSeasonActivePartsCount) {
                $nextSeasonActivePartsCount = 1;
            }
            if (!$userLessonVideoSeason) {

                $userLessonVideoSeasonModel = new UserLessonVideoSeason([
                    'parent_video_id' => $this->parent_id,
                    'user_id' => user('id'),
                    'season_id' => $nextSeason->id,
                    'copmleted_count' => $completedLearnedLessonCount,
                    'lesson_count' => $nextSeasonActivePartsCount,
                    'completed_percent' => ($completedLearnedLessonCount / $nextSeasonActivePartsCount) * 100
                ]);

                $userLessonVideoSeasonModel->save();
            } else {

                $userLessonVideoSeason->copmleted_count = $completedLearnedLessonCount;
                $userLessonVideoSeason->lesson_count = $nextSeason->getActivePartsCount();
                $userLessonVideoSeason->completed_percent = ($completedLearnedLessonCount / $nextSeasonActivePartsCount) * 100;
                $userLessonVideoSeason->save();
            }

            $nextVideo = Video::find()
                ->andWhere(['>', 'sort_order', $this->sort_order])
                ->publishedDate()
                ->active()
                ->published()
                ->partial()
                ->andWhere(['season_id' => $nextSeason->id])
                ->orderBy(['sort_order' => SORT_ASC])
                ->one();

            if ($nextVideo) {

                $learnedLesson = LearnedLesson::find()
                    ->andWhere(['video_id' => $nextVideo->id])
                    ->andWhere(['season_id' => $nextVideo->season_id])
                    ->andWhere(['parent_id' => $nextVideo->parent->id])
                    ->andWhere(['user_id' => user('id')])
                    ->one();

                if (!$learnedLesson) {

                    $learnedLessonModel = new LearnedLesson([
                        'video_id' => $nextVideo->id,
                        'user_id' => user('id'),
                        'is_completed' => LearnedLesson::$is_completed_no,
                        'parent_id' => $nextVideo->parent->id,
                        'season_id' => $nextVideo->season_id,
                    ]);

                    $learnedLessonModel->save();
                }
            }
        }
    }

    /**
     * Xozir ko'rayotgan seasondan Keyingi videoni aktiv qilish
     */
    public function currentActiveLearnedLesson(VideoSeason $currentSeason)
    {

        $nextVideo = Video::find()
            ->andWhere(['>', 'sort_order', $this->sort_order])
            ->publishedDate()
            ->active()
            ->published()
            ->partial()
            ->andWhere(['season_id' => $currentSeason->id])
            ->orderBy(['sort_order' => SORT_ASC])
            ->one();

        if ($nextVideo) {

            $learnedLesson = LearnedLesson::find()
                ->andWhere(['video_id' => $nextVideo->id])
                ->andWhere(['season_id' => $nextVideo->season_id])
                ->andWhere(['parent_id' => $nextVideo->parent->id])
                ->andWhere(['user_id' => user('id')])
                ->one();

            if (!$learnedLesson) {

                $learnedLessonModel = new LearnedLesson([
                    'video_id' => $nextVideo->id,
                    'user_id' => user('id'),
                    'is_completed' => LearnedLesson::$is_completed_no,
                    'parent_id' => $nextVideo->parent->id,
                    'season_id' => $nextVideo->season_id,
                ]);

                $learnedLessonModel->save();
            }
        }
    }

}