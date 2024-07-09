<?php

use yii\db\Migration;

/**
 * Class m240708_053104_add_unique_index_to_product_image_table
 */
class m240708_053104_add_unique_index_to_product_image_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createIndex(
            'idx-unique-product_id-color_id',
            'product_image',
            ['product_id', 'color_id'],
            true
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-unique-product_id-size_id', 'assign_product_size');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240708_053104_add_unique_index_to_product_image_table cannot be reverted.\n";

        return false;
    }
    */
}
