<?php
/**
 * @author uluGbek <muhammadjonovulugbek98@gmail.com>
 * @link https://t.me/U_Muhammadjonov
 * @date 13-Apr-24, 14:44
 */

namespace api\modules\profile\controllers;

use api\controllers\ApiBaseController;
use api\modules\book\models\Book;
use api\modules\book\models\BookPart;
use api\modules\book\models\BookSection;
use common\modules\book\models\BookPromoCode;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

class BookController extends ApiBaseController
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
    public function actionMyBooks()
    {
        $query = user()
            ->getUsedPromoCodeBooks();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->success($dataProvider);
    }

    /**
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionDetail()
    {
        $bookKey = Yii::$app->request->get('bookKey');

        $book = Book::findActiveModelBySlug($bookKey);

        BookSection::setFields([
            'id',
            'name',
            'parts',
        ]);

        BookPart::setFields([
            'id',
            'name',
            'slug',
            'is_free',
            'duration'
//            'source'
        ]);

        Book::setFields([
            'id',
            'name',
            'description',
            'discount_description',
            'author',
            'imageUrl',
            'slug',
            'is_pre_order',
            'sections'
        ]);

        $userBookPromoCode = BookPromoCode::find()
            ->andWhere(['book_id' => $book->id])
            ->andWhere(['user_id' => user('id')])
            ->andWhere(['is_use' => true])
            ->one();

        if (!$userBookPromoCode) {
            return $this->error("Sizga ushbu audioga ruxsat mavjud emas!");
        }

        return $this->success($book);
    }

    /**
     * @return array|mixed
     */
    public function actionWatch()
    {
        $bookPartKey = Yii::$app->request->get('bookPartKey');

        $bookPart = BookPart::findActiveModelBySlug($bookPartKey);

        $userBookPromoCode = BookPromoCode::find()
            ->andWhere(['book_id' => $bookPart->book->id])
            ->andWhere(['user_id' => user('id')])
            ->andWhere(['is_use' => true])
            ->one();

        if (!$userBookPromoCode) {
            return $this->error("Sizga ushbu audioga ruxsat mavjud emas!");
        }

        BookPart::setFields([
            'id',
            'name',
            'slug',
            'duration',
            'source'
        ]);


        return $this->success($bookPart);
    }
}