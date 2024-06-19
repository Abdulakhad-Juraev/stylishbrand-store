<?php
/**
 * @author uluGbek <muhammadjonovulugbek98@gmail.com>
 * @link https://t.me/U_Muhammadjonov
 * @date 28-Mar-24, 14:50
 */

use common\modules\video\models\search\VideoSearch;
use common\modules\video\models\Video;
use soft\helpers\Html;
use soft\widget\bs4\Card;
use yii\bootstrap4\Tabs;

/* @var $this soft\web\View */
/* @var $model Video */
/* @var $searchModel VideoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Videolar', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php Card::begin() ?>

<?= Tabs::widget([
    'itemOptions' => ['class' => 'pt-3'],
    'encodeLabels' => false,
    'items' => [
        [
            'label' => Html::withIcon("Umumiy ma'lumotlar", 'info-circle'),
            'content' => $this->render('_view-general', ['model' => $model]),
            'active' => true,
        ],
        [
            'label' => Html::withIcon("Video", 'video'),
            'content' => $this->render('_view-video', ['model' => $model]),

        ],
    ]

]) ?>

<?php Card::end() ?>
