<?php

use soft\db\Migration;

/**
* Handles the creation of table `{{%product_size}}`.
*/

class m240704_093718_create_product_size_table extends Migration
{

    public $tableName = '{{%product_size}}';

    public $blameable = true;

    public $timestamp = true;

    public $status = true;

    /**
    * {@inheritdoc}
    */

    public $multilingiualAttributes = ['name'];
    public function attributes()
    {
        return [
        'id' => $this->primaryKey(),
        'name' => $this->string(),
            ];
    }

}
