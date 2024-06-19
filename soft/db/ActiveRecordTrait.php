<?php


namespace soft\db;

use Yii;
use yii\db\ExpressionInterface;
use yii\web\NotFoundHttpException;

/**
 * Trait ActiveRecordTrait - ActiveRecord uchun find() metodlari
 * @package soft\db
 */
trait ActiveRecordTrait
{

    /**
     * @return ActiveQuery
     */
    public static function find()
    {
        return new ActiveQuery(get_called_class());
    }

    /**
     * Jadvaldagi Barcha yozuvlarni topish
     * @param int|ExpressionInterface|null $limit
     * @param int|ExpressionInterface|null $offset
     * @return static[]
     */
    public static function getAll($limit = null, $offset = null)
    {
        return static::find()->limit($limit)->offset($offset);
    }

    /**
     * Berilgan $id qiymat bo'yicha modelni topish
     * @param string|int $id
     * @return static
     * @throws yii\web\NotFoundHttpException
     */
    public static function findModel($id)
    {
        $model = static::findOne($id);
        if ($model == null) {
            throw new NotFoundHttpException(Yii::t('app', "Page not found!"));
        }
        return $model;
    }

    /**
     * Jadvaldag activ modelni topish
     * @param string|int $id
     * @return static
     */
    public static function findActiveOne($id)
    {
        return static::find()->andWhere(['id' => $id])->active()->one();
    }

    /**
     * Aktiv modelni topish
     * @param string|int $id
     * @return static
     * @throws \yii\web\NotFoundHttpException - agar model topilmasa yoki aktiv bo'lmasa
     */
    public static function findActiveModel($id)
    {
        $model = static::findActiveOne($id);
        if ($model == null) {
            throw new NotFoundHttpException(Yii::t('app', "Page not found!"));
        }
        return $model;
    }

    /**
     * @param string|null $slug
     * @return static|null
     */
    public static function findOneBySlug( $slug)
    {
        return static::find()->slug($slug)->one();
    }

    /**
     * @param string $slug
     * @return static
     * @throws \yii\web\NotFoundHttpException
     */
    public static function findModelBySlug(string $slug)
    {
        $model = static::findOneBySlug($slug);
        if ($model == null) {
            throw new NotFoundHttpException(Yii::t('app', "Page not found!"));
        }
        return $model;
    }

    /**
     * @param string|null $slug
     * @return static|null
     */
    public static function findActiveOneBySlug($slug)
    {
        return static::find()->slug($slug)->active()->one();
    }

    /**
     * @param string|null $slug
     * @return static
     * @throws \yii\web\NotFoundHttpException
     */
    public static function findActiveModelBySlug($slug)
    {
        $model = static::findActiveOneBySlug($slug);
        if ($model == null) {
            throw new NotFoundHttpException(Yii::t('app', "Page not found!"));
        }
        return $model;
    }

}
