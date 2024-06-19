<?php
/*
 * @author Shukurullo Odilov <shukurullo0321@gmail.com>
 * @link telegram: https://t.me/yii2_dasturchi
 * @date 17.03.2022, 10:40
 */

namespace soft\db;

class MigrateController extends \yii\console\controllers\MigrateController
{

    public $generatorTemplateFiles = [
        'create_table' => '@soft/views/createTableMigration.php',
        'drop_table' => '@yii/views/dropTableMigration.php',
        'add_column' => '@yii/views/addColumnMigration.php',
        'drop_column' => '@yii/views/dropColumnMigration.php',
        'create_junction' => '@yii/views/createTableMigration.php',
    ];

}
