<?php

use yii\db\Migration;

/**
 * Class m240720_053402_add_fullname_and_phone_to_order_table
 */
class m240720_053402_add_fullname_and_phone_to_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%order}}', 'fullname', $this->string());
        $this->addColumn('{{%order}}', 'phone', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%order}}', 'phone');
        $this->dropColumn('{{%order}}', 'fullname');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240720_053402_add_fullname_and_phone_to_order_table cannot be reverted.\n";

        return false;
    }
    */
}
