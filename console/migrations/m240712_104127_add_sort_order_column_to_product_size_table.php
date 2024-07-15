<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%product_size}}`.
 */
class m240712_104127_add_sort_order_column_to_product_size_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%product_size}}', 'sort_order', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%product_size}}', 'sort_order');
    }
}
