<?php
$ws = new swoole_websocket_server("0.0.0.0", 9502);

// 設置配置
$ws->set(
    array(
        'daemonize' => false,      // 是否是守護進程
        'max_request' => 10000,    // 最大連接數量
        'dispatch_mode' => 2,
        'debug_mode'=> 1,
        // 心跳檢測的設置，自動踢掉掉線的fd
        'heartbeat_check_interval' => 5,
        'heartbeat_idle_time' => 600,
    )
);

//監聽WebSocket連接打開事件
$ws->on('open', function ($ws, $request) {
    $ws->push($request->fd, "hello, welcome to chatroom\n");
});

//監聽WebSocket消息事件，其他：swoole提供了bind方法，支持uid和fd綁定
$ws->on('message', function ($ws, $frame) {
    $msg = 'from'.$frame->fd.":{$frame->data}\n";

    // 分批次發送
    $start_fd = 0;
    while(true)
    {
        // connection_list函數獲取現在連接中的fd
        $conn_list = $ws->connection_list($start_fd, 100);   // 獲取從fd之後一百個進行發送
        var_dump($conn_list);
        echo count($conn_list);

        if($conn_list === false || count($conn_list) === 0)
        {
            echo "finish\n";
            return;
        }

        $start_fd = end($conn_list);

        foreach($conn_list as $fd)
        {
            $ws->push($fd, $msg);
        }
    }
});

//監聽WebSocket連接關閉事件
$ws->on('close', function ($ws, $fd) {
    echo "client-{$fd} is closed\n";
    $ws->close($fd);   // 銷毀fd鏈接信息
});

$ws->start();