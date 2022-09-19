<?php
 
namespace app\components;
 
use yii\base\Component;
use yii\httpclient\Client;
 
class ApiComponent extends Component {
    public $token;
/**
 * @param string $username
 * @param string $token
 * @return object $response
 * Поиск публичных репозиториев у пользователя $username на GitHub
 */
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
 
}