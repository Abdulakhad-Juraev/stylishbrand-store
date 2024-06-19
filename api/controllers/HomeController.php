<?php
/**
 * @author uluGbek <muhammadjonovulugbek98@gmail.com>
 * @link https://t.me/U_Muhammadjonov
 * @date 27-Mar-24, 15:55
 */

namespace api\controllers;

use api\modules\podcast\models\Podcast;
use api\modules\video\models\Course;
use api\modules\video\models\Video;
use Yii;
use yii\data\ActiveDataProvider;

class HomeController extends ApiBaseController
{

    /**
     * @var bool
     */
    public $authRequired = true;

    /**
     * @var string[]
     */
    public $authOnly = ['latest-podcasts', 'latest-courses', 'latest-videos', 'mobile-search'];
    /**
     * @var string[]
     */
    public $authOptional = ['latest-podcasts', 'latest-courses', 'latest-videos', 'mobile-search'];
    /**
     * @var string[]
     */
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    /**
     * @return array
     */
    public function actionLatestPodcasts()
    {
        $latestPodcasts = Podcast::find()
            ->active()
            ->publishedDate()
            ->cache()
            ->limit(10)
            ->orderBy(['sort_order' => SORT_ASC,'created_at' => SORT_DESC])
            ->all();

        return $latestPodcasts ? $this->success($latestPodcasts) : $this->error("Bunday ma'lumot mavjud emas!");
    }

    /**
     * @return array
     */
    public function actionLatestCourses()
    {
        $latestCourses = Course::find()
            ->andWhere(['serial_type_id' => Video::SERIAL_TYPE_SERIAL])
            ->nonPartial()
            ->active()
            ->publishedDate()
            ->cache()
            ->localized()
            ->limit(10)
            ->orderBy(['sort_order' => SORT_ASC,'created_at' => SORT_DESC])
            ->all();

        return $latestCourses ? $this->success($latestCourses) : $this->error("Bunday ma'lumot mavjud emas!");
    }

    /**
     * @return array
     */
    public function actionLatestVideos()
    {
        $latestCourses = Video::find()
            ->andWhere(['serial_type_id' => Video::SERIAL_TYPE_SINGLE])
            ->active()
            ->publishedDate()
            ->cache()
            ->localized()
            ->limit(10)
            ->orderBy(['sort_order' => SORT_DESC])
            ->all();

        return $latestCourses ? $this->success($latestCourses) : $this->error("Bunday ma'lumot mavjud emas!");
    }

    /**
     * @return array
     */
    public function actionMobileSearch()
    {
        $searchKey = Yii::$app->request->get('searchKey');

        $videoQuery = Video::find()
            ->andWhere(['serial_type_id' => Video::SERIAL_TYPE_SINGLE])
            ->active()
            ->joinWith('translation')
            ->andFilterWhere(['like', 'video_lang.name', $searchKey])
            ->publishedDate()
            ->cache()
            ->localized()
            ->orderBy(['sort_order' => SORT_ASC,'created_at' => SORT_DESC]);

        $videoDataProvider = new ActiveDataProvider([
            'query' => $videoQuery
        ]);

        $podcastQuery = Podcast::find()
            ->active()
            ->joinWith('translation')
            ->andFilterWhere(['like', 'podcast_lang.name', $searchKey])
            ->publishedDate()
            ->cache()
            ->orderBy(['sort_order' => SORT_ASC,'created_at' => SORT_DESC]);

        $podcastDataProvider = new ActiveDataProvider([
            'query' => $podcastQuery
        ]);

        return $this->success([
            'videos' => $videoDataProvider,
            'podcasts' => $podcastDataProvider,
        ]);
    }
}