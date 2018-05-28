(function () {
    'use strict';

    var defaultChannel = 'general';
    var botName = 'ChatBot';
    var userName = prompt('Hi! I need your name for the Chat please :)');
    var ws;
    var _receiver = document.getElementById('ws-content-receiver');

    var addMessageToChannel = function (messages) {
        var obj = JSON.parse(messages);
        if (obj.action === 'message' && obj.user !== userName) {
            _receiver.innerHTML = obj.user + " : " + obj.message;
            // Add the "show" class to DIV
            _receiver.className = "showUp";

            // After 3 seconds, remove the show class from DIV
            setTimeout(function () {
                _receiver.className = _receiver.className.replace("showUp", "");
            }, 5000);
        }
    };

    var botMessageToGeneral = function (message) {
        return addMessageToChannel(JSON.stringify({
            action: 'bot-message',
            channel: defaultChannel,
            user: botName,
            message: message
        }));
    };

    // Initialize WebSocket
    ws = new WebSocket('ws://' + wsUrl);
    botMessageToGeneral('Connecting...', '');

    ws.onopen = function () {
        ws.send(JSON.stringify({
            action: 'subscribe',
            channel: defaultChannel,
            user: userName
        }));
    };

    ws.onmessage = function (event) {
        addMessageToChannel(event.data);
    };

    ws.onclose = function () {
        botMessageToGeneral('Connection closed');
    };

    ws.onerror = function () {
        botMessageToGeneral('An error occured!');
    };

    var _textInput = document.getElementById('ws-content-to-send');
    var _textSender = document.getElementById('ws-send-content');
    var enterKeyCode = 13;

    var sendTextInputContent = function () {
        // Get text input content
        var content = _textInput.value;

        // Send it to WS
        ws.send(JSON.stringify({
            action: 'message',
            user: userName,
            message: content,
            channel: 'general'
        }));
    };

    function myFunction() {
        var z = document.getElementById("ws-content-to-send").value;
        // Get the snackbar DIV
        var x = document.getElementById("snackbarBot");

        x.innerHTML = z;

        if (z.toString() !== '') {
            // Add the "show" class to DIV
            x.className = "showBot";

            // After 3 seconds, remove the show class from DIV
            setTimeout(function () {
                x.className = x.className.replace("showBot", "");
            }, 5000);
        }
    }

    _textSender.onclick = sendTextInputContent;
    _textSender.onclick = myFunction;

    _textInput.onkeyup = function (e) {
        // Check for Enter key
        if (e.keyCode === enterKeyCode) {
            myFunction();
            sendTextInputContent();
            // Reset input
            _textInput.value = '';
        }
    };
})();