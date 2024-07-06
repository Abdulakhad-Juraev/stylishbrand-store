<?php

use yii\db\Migration;

/**
 * Class m240704_093613_add_brend_id_and_content_to_product_table
 */
class m240704_093613_add_brend_id_and_content_to_product_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%brand}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'status' => $this->tinyInteger(),
        ]);

        $this->createTable('{{%brand_lang}}', [
            'id' => $this->primaryKey(),
            'owner_id' => $this->integer(),
            'language' => $this->string(6),
            'name' => $this->string(),
        ]);

        $this->addForeignKey('fk_brand_lang',
            '{{%brand_lang}}', 'owner_id',
            '{{%brand}}', 'id',
            'CASCADE', 'CASCADE');

        $this->addColumn('{{%product}}', 'brand_id', $this->integer());
        $this->addColumn('{{%product}}', 'content', $this->text());
        $this->addForeignKey('fk_product_brand_id', '{{%product}}', 'brand_id', '{{%brand}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_product_brand_id', '{{%product}}');
        $this->dropColumn('{{%product}}', 'content');
        $this->dropColumn('{{%product}}', 'brand_id');
        $this->dropForeignKey('fk_brand_lang', '{{%brand_lang}}');
        $this->dropTable('{{%brand_lang}}');
        $this->dropTable('{{%brand}}');

    }
}
