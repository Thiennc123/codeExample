<?php
namespace app\modules\api\migrations;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%tasks}}`.
 */
class m200729_084707_create_tasks_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tasks}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'details' => $this->text(),
            'priority' => $this->string()->notNull(),
            'due_date' => $this->dateTime(),
            'status' => $this->string()->notNull(),
            'creator_id' => $this->integer(),
            'assignee_id' => $this->integer(),
            'property_id' => $this->integer(),
            'primary_object_id' => $this->integer(),
            'location' => 'point',
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%tasks}}');
    }
}
