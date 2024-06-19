<?php
/*
 * @author Shukurullo Odilov <shukurullo0321@gmail.com>
 * @link telegram: https://t.me/yii2_dasturchi
 * @date 17.03.2022, 10:52
 */

/**
 * This view is used by console/controllers/MigrateController.php.
 *
 * The following variables are available in this view:
 */
/* @var $className string the new migration class name without namespace */
/* @var $namespace string the new migration class namespace */
/* @var $table string the name table */
/* @var $tableComment string the comment table */
/* @var $fields array the fields */
/* @var $foreignKeys array the foreign keys */

echo "<?php\n";
if (!empty($namespace)) {
    echo "\nnamespace {$namespace};\n";
}
?>

use soft\db\Migration;

/**
* Handles the creation of table `<?= $table ?>`.
*/

class <?= $className ?> extends Migration
{

    public $tableName = '<?= $table ?>';

    public $blameable = true;

    public $timestamp = true;

    public $status = false;

    /**
    * {@inheritdoc}
    */
    public function attributes()
    {
        return [
<?php foreach ($fields as $field):
    if (empty($field['decorators'])): ?>
        '<?= $field['property'] ?>',
    <?php else: ?>
        <?= "'{$field['property']}' => \$this->{$field['decorators']}" ?>,
    <?php endif;
endforeach; ?>
        ];
    }

}
