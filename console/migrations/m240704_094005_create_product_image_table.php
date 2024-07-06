<?php

use soft\db\Migration;

/**
 * Handles the creation of table `{{%product_image}}`.
 */
class m240704_094005_create_product_image_table extends Migration
{

    public $tableName = '{{%product_image}}';

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
            'color_id' => $this->integer(),
            'image' => $this->string(),
            'product_id' => $this->integer(),
        ];
    }

}
