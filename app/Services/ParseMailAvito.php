<?php


namespace App\Services;


use App\Client;
use App\Order;
use App\Services\IMAP\AvitoGetMessage;
use App\Services\Parsers\AvitoMessage;

class ParseMailAvito
{
    private $parserMessages;

    /**
     * @return array
     */
    protected function getMessages() : array
    {
        return AvitoGetMessage::getPost();
    }

    /**
     *
     */
    public function process()
    {
        $messages = $this->getMessages();
        foreach ($messages as $message) {
            if(preg_match('/^Вам пришло новое сообщение/', trim($message['subject'])) ||
                preg_match('/^Вам пришли новые сообщения/', trim($message['subject'])) ){
                $this->getParser()->setString($message['body']);
                $numbs = $this->getParser()->parsePhoneNumber();
                if(!empty($numbs)){
                    $client = Client::getClientByPhone(Client::preparePhone($numbs[0]));
                    if(!$client){
                        $client = Client::create([
                            'name' => 'Запрос с авито',
                            'phone' => $numbs[0]
                        ]);
                    }
                    $client->orders()->create([
                        'store_text' => 'Обращение с Авито',
                        'comment' => $this->getParser()->parseProduct()
                    ]);

                }
            }
        }
    }

    /**
     * @return AvitoMessage
     */
    protected function getParser() : AvitoMessage
    {
        if(!$this->parserMessages){
            $this->parserMessages = new AvitoMessage();
        }

        return $this->parserMessages;
    }
}