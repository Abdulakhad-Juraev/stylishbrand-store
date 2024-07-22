<?php

use yii\db\Migration;

/**
 * Class m240722_085750_add_in_menu_to_category_table
 */
class m240722_085750_add_in_menu_to_category_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%category}}', 'in_menu', $this->tinyInteger());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%category}}', 'in_menu');
    }


}
