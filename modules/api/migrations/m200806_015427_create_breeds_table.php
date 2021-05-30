<?php
namespace app\modules\api\migrations;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%breeds}}`.
 */
class m200806_015427_create_breeds_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%breeds}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'slug' => $this->string(),
            'property_id' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%breeds}}');
    }
}
