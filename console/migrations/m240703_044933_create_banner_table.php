<?php

use soft\db\Migration;

/**
 * Handles the creation of table `{{%banner}}`.
 */
class m240703_044933_create_banner_table extends Migration
{

    public $tableName = '{{%banner}}';

    public $blameable = true;

    public $timestamp = true;

    public $status = true;

    public $multilingiualAttributes = ['title', 'description'];

    /**
     * {@inheritdoc}
     */
    public function attributes()
    {
        return [
            'id' => $this->primaryKey(),
            'title' => $this->string(),
            'description' => $this->string(1024),
            'image' => $this->string(),
            'count' => $this->integer(),
            'button_url' => $this->string(1024)
        ];
    }

}
