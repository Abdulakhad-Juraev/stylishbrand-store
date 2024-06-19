<?php
/*
 * @author Shukurullo Odilov
 * @link telegram: https://t.me/yii2_dasturchi
 * @date 13.07.2021, 15:18
 */


/* @var $this soft\web\View */
/* @var $model backend\modules\regionmanager\models\District */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Tumanlar', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>


    <?= \soft\widget\bs4\DetailView::widget([
        'model' => $model,
        'panel' => $this->isAjax ? false : [],
        'attributes' => [
              'id', 
              'region_id', 
              'name_uz', 
              'name_oz', 
              'name_ru', 
        ],
    ]) ?>