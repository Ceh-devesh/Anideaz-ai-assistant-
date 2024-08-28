<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ChatGPT App</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        #chat-container {
            width: 400px;
            background: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        #chat-box {
            height: 300px;
            overflow-y: auto;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        .message {
            margin-bottom: 10px;
        }
        .user {
            color: blue;
        }
        .response {
            color: green;
        }
        #question-input {
            width: calc(100% - 22px);
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        #send-btn {
            padding: 10px 20px;
            background: blue;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div id="chat-container">
        <div id="chat-box"></div>
        <input type="text" id="question-input" placeholder="Enter your question">
        <button id="send-btn">Send</button>
    </div>

    <script>
        const sendBtn = document.getElementById('send-btn');
        const questionInput = document.getElementById('question-input');
        const chatBox = document.getElementById('chat-box');

        sendBtn.addEventListener('click', async () => {
            const question = questionInput.value;
            if (question) {
                addMessage('user', question);
                questionInput.value = '';

                const response = await fetch(window.location.href, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ prompt: question })
                });

                const data = await response.json();
                const answer = data.choices[0].text.trim();
                addMessage('response', answer);
            }
        });

        function addMessage(type, text) {
            const messageDiv = document.createElement('div');
            messageDiv.classList.add('message', type);
            messageDiv.textContent = text;
            chatBox.appendChild(messageDiv);
            chatBox.scrollTop = chatBox.scrollHeight;
        }
    </script>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        $prompt = $data['prompt'];

        $api_key = 'sk-proj-pBF1LxhOMtsOwf4qKlbiT3BlbkFJndvotJlan9gAHaGgP7tM';
        $url = 'https://api.openai.com/v1/engines/davinci-codex/completions';

        $post_data = array(
            'prompt' => $prompt,
            'max_tokens' => 150,
        );

        $headers = array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $api_key,
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        curl_close($ch);

        echo $response;
    }
    ?>
</body>
</html>
