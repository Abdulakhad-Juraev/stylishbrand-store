<?php

use soft\db\Migration;

/**
 * Handles the creation of table `{{%sub_category}}`.
 */
class m240619_102446_create_sub_category_table extends Migration
{

    public $tableName = '{{%sub_category}}';

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
        ],
    ];

    /**
     * {@inheritdoc}
     */
    public function attributes()
    {
        return [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'category_id' => $this->integer(),
        ];
    }

}
