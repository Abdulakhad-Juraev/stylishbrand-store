<?php

use yii\db\Migration;

/**
 * Class m240706_074222_add_unique_index_to_assign_product_size_table
 */
class m240706_074222_add_unique_index_to_assign_product_size_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createIndex(
            'idx-unique-product_id-size_id',
            'assign_product_size',
            ['product_id', 'size_id'],
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

}
