<?php

use yii\db\Migration;

/**
 * Class m240620_101203_add_column_price_to_product_table
 */
class m240620_101203_add_column_price_to_product_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%product}}', 'price', $this->float());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%product}}', 'price');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240620_101203_add_column_price_to_product_table cannot be reverted.\n";

        return false;
    }
    */
}
