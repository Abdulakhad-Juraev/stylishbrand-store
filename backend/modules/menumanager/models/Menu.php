<?php

namespace backend\modules\menumanager\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 *
 * @property-read Menu[] $subMenus
 * @property-read Menu[] $activeSubMenus
 * @property-read bool $hasActiveSubMenus
 * @property-read string $title
 * @property-read string $url
 * @property bool $child_allowed [tinyint(1)]
 * @property string $title_uz [varchar(255)]
 * @property string $title_kr [varchar(255)]
 * @property string $url_str [varchar(255)]
 * @property string $url_type [varchar(255)]
 * @property string $url_value [varchar(255)]
 * @property string $title_ru [varchar(255)]
 * @property string $title_en [varchar(255)]
 * @property bool $status [tinyint]
 * @property string $idn [varchar(255)]
 */
class Menu extends \kartik\tree\models\Tree
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%menu}}';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = parent::rules();
        return array_merge($rules, [
            [['name', 'title_uz', 'title_ru', 'title_en', 'title_kr' /*'url_type', 'url_value'*/], 'required'],
            ['url_str', 'unique'],
            ['status', 'integer'],
            [['idn','title_kr'], 'string', 'max' => 255],
            [['url_str', 'url_type', 'url_value'], 'string', 'max' => 255],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('app', 'Name'),
            'title_uz' => Yii::t('app', 'Title') . ' [' . Yii::t('app', 'uz') . ']',
            'title_ru' => Yii::t('app', 'Title') . ' [' . Yii::t('app', 'ru') . ']',
            'title_en' => Yii::t('app', 'Title') . ' [' . Yii::t('app', 'en') . ']',
            'title_kr' => Yii::t('app', 'Title') . ' [' . Yii::t('app', 'kr') . ']',
            'url_str' => Yii::t('app', 'Identifikator'),
            'url_type' => Yii::t('app', 'Url Type'),
            'url_value' => Yii::t('app', 'Url Value'),
        ];
    }

    public static function find()
    {
        $query = parent::find();
        return $query->addOrderBy('root, lft');
    }

    public static function getMenu($menuId = '')
    {
        return static::findOne(['url_str' => $menuId]);

    }

    public function getSubMenus()
    {
        return $this->hasMany(Menu::class, ['root' => 'root'])
            ->andWhere(['lvl' => $this->lvl + 1])
            ->andWhere(['>', 'lft', $this->lft])
            ->andWhere(['<', 'rgt', $this->rgt]);
    }

    public function getActiveSubMenus()
    {
        return $this->getSubMenus()
            ->andWhere(['status' => 1, 'active' => 1, 'disabled' => 0]);
    }

    /**
     * Check if menu has active sub menu
     **/

    public function getHasActiveSubMenus()
    {
        return !empty($this->activeSubMenus);
    }

    public function getTitle()
    {

        if (Yii::$app->language == 'ru') {

            return $this->title_ru;
        }
        if (Yii::$app->language == 'uz') {

            return $this->title_uz;
        }
        if (Yii::$app->language == 'en') {

            return $this->title_en;
        }
        if (Yii::$app->language == 'kr') {

            return $this->title_kr;
        }

    }

    public function getUrl($params = [])
    {
        $model = $this;
        $menuParams = [];
        if ($model->url_type === 'main') $menuParams = ['site/index'];
        if ($model->url_type === 'page') $menuParams = ['site/page', 'slug' => $model->url_value];
        if ($model->url_type === 'interactive_services') $menuParams = $model->url_value;
        if ($model->url_type === 'category') $menuParams = ['regional-management-category/index', 'id' => $model->url_value];
        if ($model->url_type === 'c-action') $menuParams = [$model->url_value];
        if ($model->url_type === 'leadership-category') $menuParams = ['leadership/index', 'category_id' => $model->url_value];
        if ($model->url_type === 'video-category') $menuParams = ['video/index', 'category_id' => $model->url_value];
        if ($model->url_type === 'page') $menuParams = ['page/index', 'slug' => $model->url_value];
        if ($model->url_type === 'other') $menuParams = ["site/$model->url_value"];

        $params = ArrayHelper::merge($menuParams, $params);
        return Url::to($params);

    }

}
