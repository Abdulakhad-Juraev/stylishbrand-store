<?php

use yii\db\Migration;

/**
 * Class m240318_073525_add_image_column_user_table
 */
class m240318_073525_add_image_column_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%user}}', 'image', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%user}}', 'image');
    }

}
