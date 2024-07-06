<?php

use soft\db\Migration;

/**
 * Handles the creation of table `{{%order}}`.
 */
class m240624_054545_create_order_table extends Migration
{

    public $tableName = '{{%order}}';

    public $blameable = true;

    public $timestamp = true;

    public $status = true;

    public $foreignKeys = [
        [
            'columns' => 'user_id',
            'refTable' => 'user',
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
            'user_id' => $this->integer(),
            'order_type' => $this->string(),
            'payment_type' => $this->string(),
            'total_price' => $this->float()
        ];
    }

}
