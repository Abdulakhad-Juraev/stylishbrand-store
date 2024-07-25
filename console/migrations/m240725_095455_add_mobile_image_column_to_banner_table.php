<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%banner}}`.
 */
class m240725_095455_add_mobile_image_column_to_banner_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%banner}}', 'mobile_image', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%banner}}', 'mobile_image');
    }
}
