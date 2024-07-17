<?php

use yii\db\Migration;

/**
 * Class m240717_090213_add_type_column_banner_table
 */
class m240717_090213_add_type_column_banner_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%banner}}', 'type', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%banner}}', 'type');
    }
}
