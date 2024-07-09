<?php

use soft\db\Migration;

/**
 * Handles the creation of table `{{%country}}`.
 */
class m240708_095747_create_country_table extends Migration
{

    public $tableName = '{{%country}}';

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
            'name' => $this->string()
        ];
    }

}
