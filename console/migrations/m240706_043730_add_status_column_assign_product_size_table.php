<?php

use yii\db\Migration;

/**
 * Class m240706_043730_add_status_column_assign_product_size_table
 */
class m240706_043730_add_status_column_assign_product_size_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%assign_product_size}}', 'status', $this->tinyInteger());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%assign_product_size}}', 'status');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240706_043730_add_status_column_assign_product_size_table cannot be reverted.\n";

        return false;
    }
    */
}
