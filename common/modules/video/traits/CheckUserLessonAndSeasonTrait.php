<?php
/**
 * @author uluGbek <muhammadjonovulugbek98@gmail.com>
 * @link https://t.me/U_Muhammadjonov
 * @date 24-Apr-24, 09:15
 */

namespace common\modules\video\traits;

use common\modules\user\models\LearnedLesson;
use common\modules\video\models\UserLessonVideoSeason;
use common\modules\video\models\Video;

trait CheckUserLessonAndSeasonTrait
{

    /**
     * @return void
     */
    public function setFirstSaveLearnedLessonAndUserSeason()
    {
        $firstSeason = $this->getSeasons()->active()
            ->one();

        if ($firstSeason) {
            $userLessonVideoSeason = UserLessonVideoSeason::find()
                ->andWhere(['parent_video_id' => $this->id])
                ->andWhere(['user_id' => user('id')])
                ->andWhere(['season_id' => $firstSeason->id])
                ->one();

            if (!$userLessonVideoSeason) {

                $newUserLessonVideoSeason = new UserLessonVideoSeason([
                    'user_id' => user('id'),
                    'season_id' => $firstSeason->id,
                    'parent_video_id' => $this->id,
                    'copmleted_count' => 0,
                    'lesson_count' => $firstSeason->getActivePartsCount(),
                    'completed_percent' => 0,
                ]);

                $newUserLessonVideoSeason->save();

                $firstVideo = Video::find()
                    ->publishedDate()
                    ->active()
                    ->published()
                    ->partial()
                    ->andWhere(['season_id' => $firstSeason->id])
                    ->orderBy(['sort_order' => SORT_ASC])
                    ->one();

                if ($firstVideo) {

                    $learnedLessonModel = new LearnedLesson([
                        'video_id' => $firstVideo->id,
                        'user_id' => user('id'),
                        'is_completed' => LearnedLesson::$is_completed_no,
                        'parent_id' => $this->id,
                        'season_id' => $firstSeason->id,
                    ]);

                    $learnedLessonModel->save();
                }

            }

        }
    }
}