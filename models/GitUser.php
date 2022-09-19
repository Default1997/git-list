<?php

namespace app\models;
use \yii\db\ActiveRecord;
use yii\web\NotFoundHttpException;

use Yii;

/**
 * This is the model class for table "git_user".
 *
 * @property int $id
 * @property string $username
 *
 * @property Repository[] $repositories
 */
class GitUser extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'git_user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username'], 'required'],
            [['username'], 'string', 'max' => 255],
            [['username'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
        ];
    }

    /**
     * Gets query for [[Repositories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRepositories()
    {
        return $this->hasMany(Repository::class, ['user_id' => 'id']);
    }

    /**
     * @param mixed $insert
     * @param array $changedAttributes
     * @return $insert $changedAttributes unchanged
     * Добавление информации о репозиториях после добавления нового пользователя
     */
    public function afterSave($insert, $changedAttributes)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $gitUser = new GitUser();

            $exist = GitUser::find()->where(['username' => $this->username])->one();
            if ($this->username == $exist->username) {
                Repository::deleteAll(['user_id' => $exist->id]);
            }

            $response = Yii::$app->apiComponent->getResponse($this->username);     

            if (!isset($response->data[0])) {
                throw new NotFoundHttpException('Пользователя не существует на GitHub или у него нет публичных репозиториев');
            }

            foreach ($response->data as $repo) {
                Yii::$app->addRepoComponent->addRepository($repo, $this->id);
            }
            
            $transaction->commit();

            return parent::afterSave($insert, $changedAttributes);
        } catch (Exception $e) {
            $transaction->rollBack();
        }
    }
}
