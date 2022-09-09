<?php

use yii\db\Migration;

/**
 * Class m220908_063344_repository
 */
class m220908_063344_repository extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%repository}}', [
            'user_id' => $this->integer()->notNull(),
            'name' => $this->string(100)->notNull()->unique(),
            'link' => $this->string(400)->notNull(),
            'updated_at' => $this->string(100)->notNull(),
            
        ], $tableOptions);
 
        $this->createIndex(
            'idx-git_user-user_id',
            '{{git_user}}',
            'id'
        );

        $this->addForeignKey(
            'FK_repo',  // это "условное имя" ключа
            'repository', // это название текущей таблицы
            'user_id', // это имя поля в текущей таблице, которое будет ключом
            'git_user', // это имя таблицы, с которой хотим связаться
            'id', // это поле таблицы, с которым хотим связаться
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('repository');

        $this->dropForeignKey(
            'FK_repo',
            'git_user'
        );
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220908_063344_repository cannot be reverted.\n";

        return false;
    }
    */
}
