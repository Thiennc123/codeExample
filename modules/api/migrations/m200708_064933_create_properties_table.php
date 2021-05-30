<?php
namespace app\modules\api\migrations;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%properties}}`.
 */
class m200708_064933_create_properties_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%properties}}', [
            'id' => $this->primaryKey(),
            'number' => $this->string()->notNull(),
            'own_id' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
            'address' => $this->string()->notNull(),
            'country' => $this->string()->notNull(),
            'state' => $this->string()->notNull(),
            'postcode' => $this->string(),
            'type' => $this->string(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%properties}}');
    }
}
