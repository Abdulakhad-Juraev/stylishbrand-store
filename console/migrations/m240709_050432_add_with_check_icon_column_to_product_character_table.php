<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%product_character}}`.
 */
class m240709_050432_add_with_check_icon_column_to_product_character_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%product_character}}', 'with_check_icon', $this->tinyInteger());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%product_character}}', 'with_check_icon');
    }
}
