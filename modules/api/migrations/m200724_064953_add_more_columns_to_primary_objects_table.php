<?php
namespace app\modules\api\migrations;
use yii\db\Migration;

/**
 * Handles adding columns to table `{{%primary_objects}}`.
 */
class m200724_064953_add_more_columns_to_primary_objects_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%primary_objects}}', 'color', $this->string());
        $this->addColumn('{{%primary_objects}}', 'description', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%primary_objects}}', 'color');
        $this->dropColumn('{{%primary_objects}}', 'description');
    }
}
