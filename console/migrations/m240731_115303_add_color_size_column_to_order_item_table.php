<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%order_item}}`.
 */
class m240731_115303_add_color_size_column_to_order_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%order_item}}', 'color', $this->string());
        $this->addColumn('{{%order_item}}', 'size', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%order_item}}', 'size');
        $this->dropColumn('{{%order_item}}', 'color');
    }
}
