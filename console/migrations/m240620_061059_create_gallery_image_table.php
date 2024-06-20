<?php

use soft\db\Migration;

/**
 * Handles the creation of table `{{%gallery_image}}`.
 */
class m240620_061059_create_gallery_image_table extends Migration
{

    public $tableName = '{{%gallery_image}}';

    public $blameable = false;

    public $timestamp = false;

    public $status = false;

    /**
     * {@inheritdoc}
     */
    public function attributes()
    {
        return [
            'id' => $this->primaryKey(),
            'type' => $this->string(),
            'ownerId' => $this->string()->notNull(),
            'rank' => $this->string()->notNull()->defaultValue(0),
            'name' => $this->string(),
            'description' => $this->text()
        ];
    }

}
