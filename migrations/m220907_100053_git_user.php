<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m220907_100053_git_user
 */
class m220907_100053_git_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('git_user', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220907_100053_git_user cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220907_100053_git_user cannot be reverted.\n";

        return false;
    }
    */
}
