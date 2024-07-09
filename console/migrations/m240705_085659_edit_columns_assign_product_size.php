<?php

use yii\db\Migration;

/**
 * Class m240705_085659_edit_columns_assign_product_size
 */
class m240705_085659_edit_columns_assign_product_size extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%assign_product_size}}','product_id',$this->integer());
        $this->alterColumn('{{%assign_product_size}}','size_id',$this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('{{%assign_product_size}}','product_id',$this->string());
        $this->alterColumn('{{%assign_product_size}}','size_id',$this->string());
    }


}
