<?php

use soft\db\Migration;

/**
 * Handles the creation of table `{{%category_character}}`.
 */
class m240708_113241_create_category_character_table extends Migration
{

    public $tableName = '{{%category_character}}';

    public $blameable = true;

    public $timestamp = true;

    public $status = true;
    public $multilingiualAttributes = ['name'];
    public $foreignKeys = [
        [
            'columns' => 'category_id',
            'refTable' => 'category',
            'refColumns' => 'id',
            'delete' => 'CASCADE',
        ]
    ];

    /**
     * {@inheritdoc}
     */
    public function attributes()
    {
        return [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'category_id' => $this->integer()
        ];
    }

}
