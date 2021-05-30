<?php
namespace app\modules\api\migrations;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%assets}}`.
 */
class m200804_062949_create_assets_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%assets}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
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
        $this->dropTable('{{%assets}}');
    }
}
