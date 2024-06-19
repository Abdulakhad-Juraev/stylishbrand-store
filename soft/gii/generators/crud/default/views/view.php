<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();

echo "<?php\n";
?>


/* @var $this soft\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */

$this->title = $model-><?= $generator->getNameAttribute() ?>;
$this->params['breadcrumbs'][] = ['label' => <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

    <?= "<?= " ?>\soft\widget\bs4\DetailView::widget([
        'model' => $model,
        'attributes' => [
<?php
if (($tableSchema = $generator->getTableSchema()) === false) {
    foreach ($generator->getColumnNames() as $name) {
        echo "              '" . $name . "',\n";
    }
} else {
    foreach ($generator->getTableSchema()->columns as $column) {
            echo "              '".$column->name."', \n";
    }
}
echo "'created_at',\n'createdBy.fullname',\n'updated_at',\n'updatedBy.fullname'";

?>
        ],
    ]) ?>
