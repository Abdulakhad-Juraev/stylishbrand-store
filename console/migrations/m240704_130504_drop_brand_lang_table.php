<?php

use yii\db\Migration;

/**
 * Handles the dropping of table `{{%brand_lang}}`.
 */
class m240704_130504_drop_brand_lang_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropTable('{{%brand_lang}}');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->createTable('{{%brand_lang}}', [
            'id' => $this->primaryKey(),
        ]);
    }
}
