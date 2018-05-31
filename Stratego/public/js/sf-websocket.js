(function () {
    'use strict';

    var defaultChannel = 'general';
    var botName = 'ChatBot';
    var ws;
    var _receiver = document.getElementById('ws-content-receiver');

    var addMessageToChannel = function (messages) {
        var obj = JSON.parse(messages);
        //console.log("channel message reçu : "+obj.channel +"channel actuel : "+ gameChannel);
        if (obj.action === 'message' && obj.user !== userName && obj.channel === gameChannel) {
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
            channel: gameChannel,
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
            channel: gameChannel,
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
            channel: gameChannel
        }));
    };

    // ----------- Listners on floting buttons
    var float_btn1 = document.getElementById("btn1");
    float_btn1.onclick = function btnFloat() {
        var text = document.getElementById("text1");
        var monTexte = text.innerText || text.textContent;
        _textInput.value = "";
        _textInput.value = monTexte;
        myFunction();
        _textInput.value = "";
    };
    var float_btn2 = document.getElementById("btn2");
    float_btn2.onclick = function btnFloat() {
        var text = document.getElementById("text2");
        var monTexte = text.innerText || text.textContent;
        _textInput.value = "";
        _textInput.value = monTexte;
        myFunction();
        _textInput.value = "";
    };
    var float_btn3 = document.getElementById("btn3");
    float_btn3.onclick = function btnFloat() {
        var text = document.getElementById("text3");
        var monTexte = text.innerText || text.textContent;
        _textInput.value = "";
        _textInput.value = monTexte;
        myFunction();
        _textInput.value = "";
    };
    var float_btn4 = document.getElementById("btn4");
    float_btn4.onclick = function btnFloat() {
        var text = document.getElementById("text4");
        var monTexte = text.innerText || text.textContent;
        _textInput.value = "";
        _textInput.value = monTexte;
        myFunction();
        _textInput.value = "";
    };
    // ----------------->

    function myFunction() {
        sendTextInputContent();
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

    _textSender.onclick = myFunction;

    _textInput.onkeyup = function (e) {
        // Check for Enter key
        if (e.keyCode === enterKeyCode) {
            myFunction();
            // Reset input
            _textInput.value = '';
        }
    };
})();