<?php

use soft\db\Migration;

/**
 * Handles the creation of table `{{%product_color}}`.
 */
class m240704_093954_create_product_color_table extends Migration
{

    public $tableName = '{{%product_color}}';

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
            'color' => $this->string(),
        ];
    }

}
