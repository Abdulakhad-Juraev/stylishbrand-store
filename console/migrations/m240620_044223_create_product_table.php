<?php

use soft\db\Migration;

/**
 * Handles the creation of table `{{%product}}`.
 */
class m240620_044223_create_product_table extends Migration
{

    public $tableName = '{{%product}}';

    public $blameable = true;

    public $timestamp = true;

    public $status = true;

    public $multilingiualAttributes = ['name', 'description'];

    public $foreignKeys = [
        [
            'columns' => 'category_id',
            'refTable' => 'category',
            'refColumns' => 'id',
            'delete' => 'CASCADE',
        ],
        [
            'columns' => 'sub_category_id',
            'refTable' => 'sub_category',
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
            'slug' => $this->string(1024),
            'description' => $this->text(),
            'image' => $this->string(),
            'category_id' => $this->integer(),
            'sub_category_id' => $this->integer(),
            'percentage' => $this->integer(),
            'published_at' => $this->integer(),
            'expired_at' => $this->integer(),
        ];
    }

}
