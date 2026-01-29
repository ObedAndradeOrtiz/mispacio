<div>

    <head>

        <style>
            .chat-container {
                width: 100%;
                max-width: 600px;
                margin: 20px auto;
                padding: 10px;
                background: #ffffff;
                border-radius: 10px;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            }

            .chat-header {
                text-align: center;
                font-size: 18px;
                font-weight: bold;
                padding: 10px;
                background-color: #007bff;
                color: white;
                border-radius: 10px 10px 0 0;
            }

            .chat-messages {
                padding: 15px;
                height: 400px;
                overflow-y: scroll;
                display: flex;
                flex-direction: column;
                gap: 10px;
            }

            .message {
                max-width: 70%;
                padding: 10px;
                border-radius: 10px;
                font-size: 14px;
                line-height: 1.5;
            }

            .message.sent {
                align-self: flex-end;
                background-color: #d1f8d1;
                color: #2d572c;
            }

            .message.received {
                align-self: flex-start;
                background-color: #f1f0f0;
                color: #333;
            }

            .timestamp {
                font-size: 10px;
                color: #888;
                text-align: right;
            }
        </style>
    </head>
    <div class="chat-container">
        <div class="chat-header">Chat Viewer</div>
        <div class="chat-messages" id="chatMessages"></div>
    </div>
    <script>
        const chatData = {
            "success": {{ $decodedJson['success'] ? 'true' : 'false' }},
            "data": @json($decodedJson['data']) // Aquí se pasa el array 'data' correctamente
        };

        const chatMessages = document.getElementById('chatMessages');

        chatData.data.forEach(message => {
            const msgDiv = document.createElement('div');
            msgDiv.classList.add('message');
            msgDiv.classList.add(message.fromMe ? 'sent' : 'received');

            const timestamp = new Date(message.timestamp * 1000).toLocaleString();

            msgDiv.innerHTML = `
                ${message.body || '<i>(No hay contenido)</i>'}
                <div class="timestamp">${timestamp}</div>`;
            chatMessages.appendChild(msgDiv);
        });
    </script>

</div>
