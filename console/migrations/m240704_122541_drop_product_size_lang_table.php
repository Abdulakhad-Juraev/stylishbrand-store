<?php

use yii\db\Migration;

/**
 * Handles the dropping of table `{{%product_size_lang}}`.
 */
class m240704_122541_drop_product_size_lang_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropTable('{{%product_size_lang}}');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->createTable('{{%product_size_lang}}', [
            'id' => $this->primaryKey(),
        ]);
    }
}
