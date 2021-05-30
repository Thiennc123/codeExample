<?php
namespace app\modules\api\migrations;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%secondary_objects}}`.
 */
class m200803_072213_create_secondary_objects_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%secondary_objects}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'type' => $this->string(),
            'location' => 'point',
            'primary_object_id' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%secondary_objects}}');
    }
}
