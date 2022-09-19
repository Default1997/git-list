<?php
 
namespace app\components;
 
use yii\base\Component;
use app\models\GitUser;
use app\models\Repository;
use Yii;
 
class CheckComponent extends Component {

    /**
     * Обновление информации о репозиториях пользователя $user->username на GitHub
     */
    public function checkUpdate()
    {
        echo 'Проверка началась';
        
        $gitUser = new GitUser();
        $transaction = Yii::$app->db->beginTransaction();

        try {
            $users = GitUser::find()->all();

            foreach ($users as $user) {
                $response = Yii::$app->apiComponent->getResponse($user->username);

                Repository::deleteAll(['user_id' => $user->id]);

                foreach ($response->data as $repo) {
                    Yii::$app->addRepoComponent->addRepository($repo, $user->id);
                }
            }

            $transaction->commit();
            echo 'Проверка закончилась';
        } catch (Exception $e) {
            $transaction->rollBack();
        }
    }

    
 
}