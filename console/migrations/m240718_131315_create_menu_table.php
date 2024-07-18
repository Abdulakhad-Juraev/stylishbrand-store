<?php

use soft\db\Migration;

/**
 * Handles the creation of table `{{%menu}}`.
 */
class m240718_131315_create_menu_table extends Migration
{

    public $tableName = '{{%menu}}';

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
            'image' => $this->string(),
            'phone' => $this->string(),
        ];
    }

}
