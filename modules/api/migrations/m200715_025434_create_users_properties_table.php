<?php
namespace app\modules\api\migrations;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%users_properties}}`.
 */
class m200715_025434_create_users_properties_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%users_properties}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'property_id' => $this->integer()->notNull(),
            'invited_token' => $this->string(),
            'permission_id' => $this->integer()->notNull(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%users_properties}}');
    }
}
