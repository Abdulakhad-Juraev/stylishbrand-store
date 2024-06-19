<?php


namespace backend\modules\menumanager\controllers;


use common\models\LeadershipCategory;

use common\models\VideoCategory;
use common\modules\page\models\Page;
use common\modules\post\models\PostCategory;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Html;
use yii\web\Controller;

class MenuController extends Controller
{

    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::className(),
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['admin'],
                        ],
                    ],
                ],
            ]
        );
    }


    private function sections()
    {
        $sections = Yii::$app->getModule('menumanager')->sections();
        $options = Html::tag('option', "Sahifani tanlang ... ");
        foreach ($sections as $route => $label) {
            $options .= Html::tag('option', $label, ['value' => $route]);
        }
        return $options;
    }

    public function actionGetValue()
    {

        $options = '';
        $type = $_GET['type'];
        if ($type == 'leadership-category') {
            $options = $this->categories();
        }
        if ($type == 'new-category') {
            $options = $this->NewsCategories();
        }
        if ($type == 'video-category') {
            $options = $this->videoCategory();
        }

        if ($type == 'c-action') {
            $options = $this->sections();
        }
        if ($type == 'page') {
            $options = $this->pages();
        }

        return Html::tag('select', $options, [
            'id' => 'tree-url_value',
            'class' => 'form-control',
            'name' => 'Menu[url_value]'
        ]);

    }

    private function categories()
    {

        $categories = LeadershipCategory::find()->all();
        $options = Html::tag('option', "Rahbariyat turini tanlang...");
        foreach ($categories as $category) {
            $options .= Html::tag('option', $category->name, ['value' => $category->id]);
        }

        return $options;
    }
    private function videoCategory()
    {

        $categories = VideoCategory::find()->andWhere(['status'=>VideoCategory::STATUS_ACTIVE])->all();
        $options = Html::tag('option', "Video turini tanlang...");
        foreach ($categories as $category) {
            $options .= Html::tag('option', $category->title, ['value' => $category->slug]);
        }

        return $options;
    }
    private function pages()
    {

        $pages = Page::find()->all();
        $options = Html::tag('option', "Sahifani tanlang...");
        foreach ($pages as $page) {
            $options .= Html::tag('option', $page->title_uz, ['value' => $page->slug]);
        }

        return $options;
    }

    private function NewsCategories()
    {

        $categories = PostCategory::find()->all();
        $options = Html::tag('option', "Yangilik turini tanlang...");
        foreach ($categories as $category) {
            $options .= Html::tag('option', $category->name, ['value' => $category->id]);
        }

        return $options;
    }

}