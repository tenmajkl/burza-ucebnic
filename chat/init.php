<?php

use App\Chat;
use Workerman\Worker;

require __DIR__.'/vendor/autoload.php';

$worker = new Worker('websocket://0.0.0.0:2346'); // todo .env

$rooms = [];
$connections = [];

$chat = new Chat();

$worker->onMessage = [$chat, 'onMessage']; 

$worker->onClose = [$chat, 'onClose'];

Worker::runAll();
