<?php

declare(strict_types=1);

namespace App;

use Workerman\Connection\TcpConnection;

class User
{
    public function __construct(
        public readonly string $session,
        public readonly int $room,
        public readonly TcpConnection $connection,
    ) {}

    public function send(\stdClass $message): self
    {
        $this->connection->send(json_encode($message));

        return $this;
    }
}
