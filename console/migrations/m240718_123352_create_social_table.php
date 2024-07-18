<?php

use soft\db\Migration;

/**
 * Handles the creation of table `{{%social}}`.
 */
class m240718_123352_create_social_table extends Migration
{

    public $tableName = '{{%social}}';

    public $blameable = true;

    public $timestamp = true;

    public $status = true;

    /**
     * {@inheritdoc}
     */
    public function attributes()
    {
        return [
            'id' => $this->primaryKey(),
            'url' => $this->string(),
            'image' => $this->string(),
        ];
    }

}
