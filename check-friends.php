<!DOCTYPE html>
<html>
    <head>
        <title>Chat Laneros</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
        <script src="./js/socket.io.js"></script>
        <script>
            var socket;
            $(document).ready(function() {
                socket = new io.connect('localhost:9876');
            
                socket.on('connect', function() {
                    console.log('Client has connected to the server!');
                });

                socket.emit('username', {user: 'colegax'});

                socket.on('summoners-info', function(data) {
                    $('#data-container').append('<div>' + data + '</div>');
                });

                socket.on('disconnect', function() {
                    console.log('The server has disconnected!');
                });
            });

            /*function sendMessageToServer() {
                var message = $('#mensaje');
                var usuario = $('#nick');
                socket.emit('chat', {usu: usuario.val(), msg: message.val()});
                message.val('').focus();
            }*/
        </script>
    </head>
    <body>
        <div style="width: 300px">
            <div id="data-container" style="height: 100%"></div>
        </div>
    </body>
</html>