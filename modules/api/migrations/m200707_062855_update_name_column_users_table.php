<?php
namespace app\modules\api\migrations;
use yii\db\Migration;

/**
 * Class m200707_062855_update_name_column_users_table
 */
class m200707_062855_update_name_column_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn('{{%users}}', 'password', 'encrypted_password');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->renameColumn('{{%users}}', 'encrypted_password', 'password');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200707_062855_update_name_column_users_table cannot be reverted.\n";

        return false;
    }
    */
}
