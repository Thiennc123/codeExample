<?php
namespace app\modules\api\migrations;
use yii\db\Migration;

/**
 * Handles adding columns to table `{{%assets}}`.
 */
class m200804_100508_add_more_columns_to_assets_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%assets}}', 'type', $this->string());
        $this->addColumn('{{%assets}}', 'tag_colour', $this->string());
        $this->addColumn('{{%assets}}', 'tag_number_range', $this->string());
        $this->addColumn('{{%assets}}', 'date_of_birth', $this->dateTime());
        $this->addColumn('{{%assets}}', 'description', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%assets}}', 'type');
        $this->dropColumn('{{%assets}}', 'tag_colour');
        $this->dropColumn('{{%assets}}', 'tag_number_range');
        $this->dropColumn('{{%assets}}', 'date_of_birth');
        $this->dropColumn('{{%assets}}', 'description');
    }
}
