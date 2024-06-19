<?php

use soft\db\Migration;

/**
 * Handles the creation of table `{{%category}}`.
 */
class m240619_095150_create_category_table extends Migration
{

    public $tableName = '{{%category}}';

    public $blameable = true;

    public $timestamp = true;

    public $status = true;

    public $multilingiualAttributes = ['name'];

    /**
     * {@inheritdoc}
     */
    public function attributes()
    {
        return [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
        ];
    }

}
