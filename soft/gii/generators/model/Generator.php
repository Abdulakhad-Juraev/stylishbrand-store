<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace soft\gii\generators\model;

use Yii;
use yii\base\NotSupportedException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Connection;
use yii\db\Schema;
use yii\db\TableSchema;
use yii\gii\CodeFile;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/**
 * This generator will generate one or multiple ActiveRecord classes for the specified database table.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 *
 * @property-read string $name
 */
class Generator extends \yii\gii\generators\model\Generator
{

    public $ns = 'backend\models';
    public $baseClass = 'soft\db\ActiveRecord';
    public $queryNs = 'backend\models\query';
    public $queryBaseClass = 'soft\db\ActiveQuery';

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'Soft Model Generator';
    }


}
