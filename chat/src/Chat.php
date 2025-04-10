<?php

declare(strict_types=1);

namespace App;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use Workerman\Connection\TcpConnection;

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

    private function handleAuth(TcpConnection $connection, \stdClass $data): void
    {
        $user = new User($data->session, $data->reservation, $connection);
        $this->users[$connection->id] = $user;
        $this->getRoom($data->reservation)->addUser($user);
    }

    private function handleMessage(TcpConnection $connection, \stdClass $data): void
    {
        if (!isset($this->users[$connection->id])) {
            return;
        }

        $user = $this->users[$connection->id];
        $room = $this->getRoom($user->room);

        if (!($message = $this->sendToServer($data->content, $user, $room->hasJustOneUser()))) {
            $this->onClose($connection);

            return;
        }

        $room->send($message);
    }

    private function sendToServer(string $message, User $user, bool $notify): false|\stdClass
    {
        $client = new Client();

        try {
            $res = $client->request('POST', 'http://localhost:8000/api/messages/'.$user->room, [
                'body' => json_encode(['content' => $message, 'notify' => (int) $notify]),
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Cookie' => 'PHP_SESSION='.$user->session,
                ],
            ]);
        } catch (ServerException $e) {
            echo $e->getResponse()->getBody();

            return false;
        } catch (ClientException $_) {
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
