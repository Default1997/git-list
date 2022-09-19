<?php
 
namespace app\components;
 
use yii\base\Component;
use app\models\Repository;
use DateTime;
 
class AddRepoComponent extends Component {

    /**
     * @param object $repo
     * @param int $userId
     * Добавление новой записи в БД о найденом репозитории
     */
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