<?php

use soft\db\Migration;

/**
 * Handles the creation of table `{{%order_item}}`.
 */
class m240624_062453_create_order_item_table extends Migration
{

    public $tableName = '{{%order_item}}';

    public $blameable = true;

    public $timestamp = true;

    public $status = false;

    public $foreignKeys = [
        [
            'columns' => 'order_id',
            'refTable' => 'order',
            'refColumns' => 'id',
            'delete' => 'CASCADE',
        ],
        [
            'columns' => 'product_id',
            'refTable' => 'product',
            'refColumns' => 'id',
            'delete' => 'CASCADE',
        ],
    ];

    /**
     * {@inheritdoc}
     */
    public function attributes()
    {
        return [
            'id' => $this->primaryKey(),
            'order_id' => $this->integer()->notNull(),
            'product_id' => $this->integer()->notNull(),
            'count' => $this->integer(),
            'price' => $this->integer(),
            'total_price' => $this->float(),
        ];
    }

}
