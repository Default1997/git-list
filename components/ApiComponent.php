<?php
 
namespace app\components;
 
use yii\base\Component;
use yii\httpclient\Client;
use app\models\GitUser;
use app\models\Repository;
use Yii;
use DateTime;
 
class ApiComponent extends Component {
    public $token;

    public function getResponse($username)
    {
        $client = new Client();
            $response = $client->createRequest()
                ->setMethod('GET')
                ->setUrl('https://api.github.com/users/' . $username . '/repos')
                ->addHeaders(['content-type' => 'application/json'])
                ->addHeaders(['Authorization' => $this->token])
                ->setOptions([
                    'userAgent' => 'Default1997',
                ])
                ->send();

        return $response;
    }

    public function checkUpdate()
    {
        echo 'Проверка началась';
        
        $gitUser = new GitUser();
        $transaction = Yii::$app->db->beginTransaction();

        try {
            $users = GitUser::find()->all();

            foreach ($users as $user) {
                $response = Yii::$app->mycomponent->getResponse($user->username);

                Repository::deleteAll(['user_id' => $user->id]);

                foreach ($response->data as $repo) {
                    Yii::$app->mycomponent->addRepository($repo, $user->id);
                }
            }

            $transaction->commit();
            echo 'Проверка закончилась';
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
 
}