<?php

use yii\db\Migration;

/**
 * Class m240708_100437_add_country_id_column_product_table
 */
class m240708_100437_add_country_id_column_product_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%product}}', 'country_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%product}}', 'country_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240708_100437_add_country_id_column_product_table cannot be reverted.\n";

        return false;
    }
    */
}
