<!DOCTYPE html>
<html>
<head>
    <title>swoole chat room</title>
    <meta charset="UTF-8">
    <script type="text/Javascript">
        if(window.WebSocket){
            var webSocket = new WebSocket("ws://192.168.42.142:9502");
            webSocket.onopen = function (event) {
                //webSocket.send("Hello,WebSocket!");
            };
            webSocket.onmessage = function (event) {
                var content = document.getElementById('content');
                content.innerHTML = content.innerHTML.concat('<p style="margin-left:20px;height:20px;line-height:20px;">'+event.data+'</p>');
            }

            var sendMessage = function(){
                var data = document.getElementById('message').value;
                webSocket.send(data);
            }
        }else{
            console.log("您的瀏覽器不支持WebSocket");
        }
    </script>
</head>
<body>
<div style="width:600px;margin:0 auto;border:1px solid #ccc;">
    <div id="content" style="overflow-y:auto;height:300px;"></div>
    <hr/>
    <div style="height:40px">
        <input type="text" id="message" style="margin-left:10px;height:25px;width:450px;">
        <button onclick="sendMessage()" style="height:28px;width:75px;">發送</button>
    </div>
</div>
</body>
</html>