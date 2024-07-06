<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%product_size}}`.
 */
class m240704_122456_add_name_column_to_product_size_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%product_size}}', 'name', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%product_size}}', 'name');
    }
}
