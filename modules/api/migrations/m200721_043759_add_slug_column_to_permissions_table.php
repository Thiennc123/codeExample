<?php
namespace app\modules\api\migrations;
use yii\db\Migration;

/**
 * Handles adding columns to table `{{%permissions}}`.
 */
class m200721_043759_add_slug_column_to_permissions_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%permissions}}', 'slug', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%permissions}}', 'slug');
    }
}
