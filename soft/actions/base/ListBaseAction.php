<?php
/*
 * @author Shukurullo Odilov
 * @link telegram: https://t.me/yii2_dasturchi
 * @date 27.12.2021, 10:56
 */

namespace soft\actions\base;

use Yii;
use yii\base\Action;
use yii\base\InvalidArgumentException;

/**
 * Base class for listing records (mainly used for `index` action)
 */
class ListBaseAction extends Action
{

    /**
     * @var string Search class name
     */
    public $searchModelClass;

    /**
     * @var string model class name
     */
    public $modelClass;

    /**
     * @var \soft\db\ActiveQuery|null
     */
    public $query;

    /**
     * @var \yii\db\ActiveRecord
     */
    public $searchModel;

    /**
     * Data provider object for GridView.
     * If not set will be automatically created according to searchModelClass
     * @var \yii\data\ActiveDataProvider|null
     */
    public $dataProvider;

    /**
     * @var int
     */
    public $defaultPageSize = 20;

    /**
     * @var array|null
     */
    public $params;

    /**
     * @var string
     */
    public $viewPath = 'index';

    /**
     * @var array
     */
    public $viewParams = [];

    public function run()
    {
        $this->normalizeAttributes();
        return $this->controller->render($this->viewPath, $this->viewParams);
    }

    public function normalizeAttributes()
    {
        if ($this->dataProvider == null) {

            if ($this->searchModelClass == null) {
                throw new InvalidArgumentException('`dataProvider` or `searchModelClass` property must be set');
            }


            if ($this->searchModel == null) {
                $this->searchModel = new $this->searchModelClass;
            }

            if ($this->query == null) {
                $this->query = $this->modelClass::find();
            }

            if ($this->params == null) {
                $this->params = Yii::$app->request->queryParams;
            }

            $this->dataProvider = $this->searchModel->search($this->query, $this->defaultPageSize, $this->params);

        }

        $this->viewParams['dataProvider'] = $this->dataProvider;
        $this->viewParams['searchModel'] = $this->searchModel;

    }


}
