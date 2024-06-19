<?php

use soft\db\Migration;

/**
 * Handles the creation of table `{{%user_device}}`.
 */
class m240318_062823_create_user_device_table extends Migration
{

    public $tableName = '{{%user_device}}';

    public $blameable = false;

    public $timestamp = true;

    public $status = true;

    public $foreignKeys = [
        [
            'columns' => 'user_id',
            'refTable' => 'user',
            'delete' => 'SET NULL',
        ],
    ];

    /**
     * {@inheritdoc}
     */
    public function attributes()
    {
        return [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'device_name' => $this->string(1024),
            'device_id' => $this->string(1024),
            'firebase_token' => $this->text(),
            'token' => $this->string(1024)
        ];
    }

}
