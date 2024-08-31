<?php

namespace App;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use Workerman\Connection\TcpConnection;
use stdClass;

class Chat
{

    private array $users = [];
    private array $handlers;
    private array $rooms = [];

    public function __construct()
    {
        $this->handlers = [
            [$this, 'handleAuth'],
            [$this, 'handleMessage'],
        ];
    }
    
    public function onMessage(TcpConnection $connection, string $message): void
    {
        $data = json_decode($message);
        $type = $data->type;
        $this->handlers[$type]($connection, $data);
    }

    public function onClose(TcpConnection $connection): void
    {
        $user = $this->users[$connection->id];
        $room = $this->rooms[$user->room];
        $room->removeUser($user);
        if ($room->empty()) {
            unset($this->rooms[$user->room]);
        }

        unset($this->users[$connection->id]);
    }

    private function handleAuth(TcpConnection $connection, stdClass $data): void
    {
        $user = new User($data->session, $data->reservation, $connection);
        $this->users[$connection->id] = $user;
        $this->getRoom($data->reservation)->addUser($user);
    }

    private function handleMessage(TcpConnection $connection, stdClass $data): void
    {
        if (!isset($this->users[$connection->id])) {
            return;
        }

        $user = $this->users[$connection->id];

        if (!($message = $this->sendToServer($data->content, $user))) {
            $this->onClose($connection);
            return;
        }

        $this->getRoom($user->room)->send($message);
    }

    private function sendToServer(string $message, User $user): false|stdClass
    {
        $client = new Client();
        try {
            $res = $client->request('POST', 'http://localhost:8000/api/messages/'.$user->room, [
                'body' => json_encode(['content' => $message]),
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Cookie' => 'PHP_SESSION='.$user->session,
                ]
            ]); 
        }
        catch (ServerException $e) {
            echo $e->getResponse()->getBody();
            return false;
        }
        catch (ClientException $_) {
            return false;
        }

        $body = $res->getBody();
        return json_decode((string) $body)->data;
    }

    private function getRoom(int $id) 
    {
        if (!isset($this->rooms[$id])) {
            $this->rooms[$id] = new Room($id);
        }

        return $this->rooms[$id];
    }
}
