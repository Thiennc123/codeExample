<?php
namespace app\modules\api\migrations;
use yii\db\Migration;

/**
 * Handles adding columns to table `{{%properties}}`.
 */
class m200713_083017_add_contact_columns_to_properties_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%properties}}', 'contact_name', $this->string());
        $this->addColumn('{{%properties}}', 'contact_email', $this->string());
        $this->addColumn('{{%properties}}', 'updated_user', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%properties}}', 'contact_name');
        $this->dropColumn('{{%properties}}', 'contact_email');
        $this->dropColumn('{{%properties}}', 'updated_user');
    }
}
