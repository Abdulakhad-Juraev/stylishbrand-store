<?php

use yii\db\Migration;

/**
 * Class m240717_095744_add_home_page_column_category_table
 */
class m240717_095744_add_home_page_column_category_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%category}}', 'home_page', $this->tinyInteger());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%category}}', 'home_page');
    }
}
