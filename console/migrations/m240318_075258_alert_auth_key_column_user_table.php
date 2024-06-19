<?php

use yii\db\Migration;

/**
 * Class m240318_075258_alert_auth_key_column_user_table
 */
class m240318_075258_alert_auth_key_column_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%user}}', 'auth_key', $this->string(255));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('{{%user}}', 'auth_key', $this->string(32));
    }
}
