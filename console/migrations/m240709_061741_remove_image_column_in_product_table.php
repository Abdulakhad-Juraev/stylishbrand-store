<?php

use yii\db\Migration;

/**
 * Class m240709_061741_remove_image_column_in_product_table
 */
class m240709_061741_remove_image_column_in_product_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('{{%product}}', 'image');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('{{%product}}', 'image', $this->string());
    }

}
