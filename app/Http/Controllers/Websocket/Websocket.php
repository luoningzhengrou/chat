<?php


namespace App\Http\Controllers\Websocket;


use App\Http\Controllers\Controller;
use Swoole\Server;

class Websocket extends Controller
{

    public function __construct()
    {
    }

    public function index()
    {
        $server = new Server('localhost',8000);
        // 启动 WebSocket 服务器
        $server->start();

        $server->on('open',function (Server $server, $request){
           echo "server : handshake success with fd{$request->fd}\n";
        });

        // 收到消息时触发推送
        $server->on('message', function (Swoole\WebSocket\Server $server, $frame) {
            echo "receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";
            $server->push($frame->fd, "this is server");
        });

        // 关闭 WebSocket 连接时触发
        $server->on('close', function ($ser, $fd) {
            echo "client {$fd} closed\n";
        });
        return ;
    }
}
