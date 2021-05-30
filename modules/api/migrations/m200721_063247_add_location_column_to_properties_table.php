<?php
namespace app\modules\api\migrations;
use yii\db\Migration;

/**
 * Handles adding columns to table `{{%properties}}`.
 */
class m200721_063247_add_location_column_to_properties_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%properties}}', 'location', 'point');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%properties}}', 'location');
    }
}
