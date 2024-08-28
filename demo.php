<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anideaz AI Assistant</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Luckiest+Guy&family=Sanchez:wght@400;700&display=swap" rel="stylesheet">
    
    <style>
        body{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        .chat-container {
            width: 90%;
            
            margin: auto;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.8); /* Slightly transparent background for better readability */
            overflow: hidden;
            padding: 70px;
           box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
           word-wrap: break-word;
          
        }

        h2 {
            font-family: 'Sanchez', sans-serif;
            color: Black;
            display: inline-block;
            
            margin-left: 40%;
        }

        h2 img {
            margin-left: 10px;
            vertical-align: middle;
        }

        h3 {
            font-family: 'Sanchez', sans-serif;
            color: #FF7F3E;
        }

        .message {
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 8px;
             word-wrap: break-word;
            clear: both;
            overflow:hidden;
  
        }

        .user-message {
            background-color: #d9f1fa;
            align-self: flex-start;
            margin-left: auto;
        }

        .bot-message {
            background-color: #282C34;
            align-self: flex-start;
            margin-right: auto;
            width:100%;
            height:100%;
            color:white;
            word-wrap: break-word;
            
           
        }

        .form-container {
            display: flex;
            margin-top: 10px;
            align-items: center;
        }

        #user-input {
            flex: 1;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 20px;
            font-size: 16px;
            margin-right: 10px;
            background-color: #fff;
        }

        #submit-button {
            background: none;
            border: none;
            cursor: pointer;
            display: flex;
            padding:20px;
            align-items: center;
        }

        #submit-button img {
            width: 50px;
            height: 50px;
        }

        /* Responsive styles */
        @media (max-width: 768px) {
            .chat-container {
                width: 95vw;
                padding: 10px;
            }

            #user-input {
                font-size: 14px;
                padding: 8px;
            }

            #submit-button img {
                width: 30px;
                height: 30px;
            }

            h2 {
                font-size: 1.5em;
            }

            h3 {
                font-size: 1.2em;
            }
        }

        @media (max-width: 480px) {
            .chat-container {
                width: 100vw;
                padding: 5px;
            }

            #user-input {
                font-size: 12px;
                padding: 6px;
            }

            #submit-button img {
                width: 25px;
                height: 25px;
            }

            h2 {
                font-size: 1.2em;
            }

            h3 {
                font-size: 1em;
            }

            .message {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="chat-container">
        <h2>Anideaz AI Assistant<img src="https://img.icons8.com/fluency/48/bot.png" alt="bot"/></h2>
        
        <div id="chat-box">
            <?php
            // Check if the form is submitted
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["user-input"])) {
                // Get user input
                $user_input = htmlspecialchars($_POST["user-input"]);

                // Display user's prompt in an h3 tag
                echo '<h3>Prompt: ' . $user_input . '</h3>';

                // Prepare data for OpenAI API request
                $openai_api_url = 'https://api.openai.com/v1/chat/completions';
                $data = array(
                    "model" => "gpt-3.5-turbo",
                    "messages" => array(
                        array(
                            "role" => "system",
                            "content" => "what is color of apple\n"
                        ),
                        array(
                            "role" => "user",
                            "content" => $user_input
                        )
                    ),
                    "temperature" => 1,
                    "max_tokens" => 4000,
                    "top_p" => 1,
                    "frequency_penalty" => 0,
                    "presence_penalty" => 0
                );

                // Convert data array to JSON
                $data_string = json_encode($data);

                // Initialize cURL session
                $ch = curl_init($openai_api_url);

                // Set cURL options
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Authorization: Bearer sk-proj-pBF1LxhOMtsOwf4qKlbiT3BlbkFJndvotJlan9gAHaGgP7tM' // Replace with your actual API key
                ));
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);

                // Execute cURL session and capture API response
                $response = curl_exec($ch);

                // Check for cURL errors
                if (curl_errno($ch)) {
                    echo '<p>Error: ' . curl_error($ch) . '</p>';
                }

                // Close cURL session
                curl_close($ch);

                // Decode JSON response
                $response_array = json_decode($response, true);

                // Display bot's response
                if (isset($response_array['choices'][0]['message']['content'])) {
                    $bot_response = htmlspecialchars($response_array['choices'][0]['message']['content']);
                    echo '<textarea style="width:100%;height:100%;overflow:hidden;position:relative">' . $bot_response . '</textarea>';
                }
            }
            ?>
        </div>

        <form class="form-container" id="message-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <input type="text" id="user-input" name="user-input" placeholder="Type your message..." required>
            <button id="submit-button" type="submit">
                <img  src="https://img.icons8.com/ios/50/circled-chevron-up.png" alt="Submit">
            </button>
        </form>
    </div>

    <script>
        // Scroll chat messages to the bottom
        var chatBox = document.getElementById('chat-box');
        chatBox.scrollTop = chatBox.scrollHeight;

        // Submit form on Enter key press
        document.getElementById('user-input').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                document.getElementById('message-form').submit();
            }
        });
        
      
    </script>
    
    

  
</body>
</html>
