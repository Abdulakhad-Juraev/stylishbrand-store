<?php

use soft\db\Migration;

/**
 * Handles the creation of table `{{%assign_product_size}}`.
 */
class m240704_093731_create_assign_product_size_table extends Migration
{

    public $tableName = '{{%assign_product_size}}';

    public $blameable = true;

    public $timestamp = true;

    public $status = false;

    /**
     * {@inheritdoc}
     */
    public function attributes()
    {
        return [
            'id' => $this->primaryKey(),
            'product_id' => $this->string(),
            'size_id' => $this->string(),
        ];
    }

}
