<?php

use yii\db\Migration;

/**
 * Class m240620_085031_edit_date_columns_from_product_table
 */
class m240620_085031_edit_date_columns_from_product_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%product}}', 'published_at', $this->bigInteger());
        $this->alterColumn('{{%product}}', 'expired_at', $this->bigInteger());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
       $this->alterColumn('{{%product}}', 'expired_at',$this->integer());
       $this->alterColumn('{{%product}}', 'published_at',$this->integer());
    }


}
