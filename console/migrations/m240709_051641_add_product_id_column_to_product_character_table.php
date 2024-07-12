<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%product_character}}`.
 */
class m240709_051641_add_product_id_column_to_product_character_table extends Migration
{

    public $foreignKeys = [
        [
            'columns' => 'product_id',
            'refTable' => 'product',
            'refColumns' => 'id',
            'delete' => 'CASCADE',
        ]
    ];


    /**
     * {@inheritdoc}
     */

    public function safeUp()
    {
        $this->addColumn('{{%product_character}}', 'product_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%product_character}}', 'product_id');
    }
}
