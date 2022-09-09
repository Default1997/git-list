<?php

namespace app\models;
use yii\httpclient\Client;
use \yii\db\ActiveRecord;
use DateTime;
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

    public function afterSave($insert, $changedAttributes)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $client = new Client();
            $gitUser = new GitUser();

            $exist = GitUser::find()->where(['username' => $this->username])->one();
            if ($this->username == $exist->username) {
                Repository::deleteAll(['user_id' => $exist->id]);
            }

            $response = $gitUser->getResponse($this->username);

            if (!isset($response->data[0])) {
                throw new NotFoundHttpException('Пользователя не существует на GitHub или у него нет публичных репозиториев');
            }

            foreach ($response->data as $repo) {
                $gitUser->addRepository($repo, $this->id);
            }
            
            $transaction->commit();

            return parent::afterSave($insert, $changedAttributes);
        } catch (Exception $e) {
            $transaction->rollBack();
        }
    }

    public function addRepository($repo, $userId)
    {
        $dateTime = new DateTime($repo[updated_at]);
        $repository = new Repository();
        $repository->user_id = $userId;
        $repository->name = $repo[full_name];
        $repository->updated_at = $dateTime->format('U');;
        $repository->link = $repo[html_url];
        $repository->save();

        if (!$repository->save()){
            var_dump($repository->getErrors());
            die();
        }
    }

    public static function checkUpdate()
    {
        echo 'Проверка началась';
        
        $gitUser = new GitUser();
        $transaction = Yii::$app->db->beginTransaction();

        try {
            $users = GitUser::find()->all();

            foreach ($users as $user) {
                $response = $gitUser->getResponse($user->username);
                Repository::deleteAll(['user_id' => $user->id]);

                foreach ($response->data as $repo) {
                    $gitUser->addRepository($repo, $user->id);
                }
            }

            $transaction->commit();
            echo 'Проверка закончилась';
        } catch (Exception $e) {
            $transaction->rollBack();
        }
    }

    public function getResponse($username)
    {
        $client = new Client();
            $response = $client->createRequest()
                ->setMethod('GET')
                ->setUrl('https://api.github.com/users/' . $username . '/repos')
                ->addHeaders(['content-type' => 'application/json'])
                ->addHeaders(['Authorization' => 'Bearer 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'])
                ->setOptions([
                    'userAgent' => 'Default1997',
                ])
                ->send();

        return $response;
    }
}
