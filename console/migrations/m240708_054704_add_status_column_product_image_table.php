<?php

use yii\db\Migration;

/**
 * Class m240708_054704_add_status_column_product_image_table
 */
class m240708_054704_add_status_column_product_image_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%product_image}}', 'status', $this->tinyInteger());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%product_image}}', 'status');
    }
}
