<?php
/*
 * @author Shukurullo Odilov
 * @link telegram: https://t.me/yii2_dasturchi
 * @date 25.05.2021, 18:41
 */

use yii\db\Migration;

/**
 * Class m210521_054641_alter_email_column_from_user_table
 */
class m210521_054641_alter_email_column_from_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('user', 'email', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return true;
    }


}
