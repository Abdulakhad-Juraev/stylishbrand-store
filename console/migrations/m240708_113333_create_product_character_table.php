<?php

use soft\db\Migration;

/**
 * Handles the creation of table `{{%product_character}}`.
 */
class m240708_113333_create_product_character_table extends Migration
{

    public $tableName = '{{%product_character}}';

    public $blameable = true;

    public $timestamp = true;

    public $status = true;

    public $foreignKeys = [
        [
            'columns' => 'category_character_id',
            'refTable' => 'category_character',
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
            'title' => $this->string(),
            'category_character_id' => $this->integer(),
        ];
    }

}
