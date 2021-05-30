<?php
namespace app\modules\api\migrations;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%mobs}}`.
 */
class m200805_022714_create_mobs_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%mobs}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'type' => $this->string(),
            'location' => 'point',
            'primary_object_id' => $this->integer(),
            'property_id' => $this->integer(),
            'tag_colour' => $this->string(),
            'tag_number_range' => $this->string(),
            'date_of_birth' => $this->dateTime(),
            'description' => $this->text(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%mobs}}');
    }
}
