<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>ChatGPT Demo</title>
   <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Luckiest+Guy&family=Sanchez:ital@0;1&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        
        .chat-container {
           width:69vw;
            margin: auto;
            border: 1px solid #ccc;
             box-shadow: rgba(50, 50, 93, 0.25) 0px 2px 5px -1px, rgba(0, 0, 0, 0.3) 0px 1px 3px -1px;
            border-radius: 5px;
            /*background-color: #f9f9f9;*/
           background: rgb(157,157,157);
            background: linear-gradient(90deg, rgba(157,157,157,1) 0%, rgba(173,169,169,1) 28%, rgba(129,132,133,1) 75%);
            overflow:hidden;
               padding: 20px;
        }
        
        .message {
            max-width: 600px;
           
            
        }
        
        .system-message {
            color: #888;
            max-width: 600px;
        }
        
        .user-message {
            background-color: #d9f1fa;
            padding: 10px;
            border-radius: 10px;
            display: inline-block;
            max-width: 80%;
        }
        
        .bot-message {
            max-width: 600px;
            background-color: #e2e2e2;
            padding: 10px;
            border-radius: 10px;
            display: inline-block;
            max-width: 85%;
        }
      
       
        
    </style>
</head>
<body>
    <div class="chat-container">
        <div style='display:flex; justify-content:center; font-family:Sanchez'>
             <h2>Anideaz AI Assistant<img style='position:relative;top:10px; margin-left:10px;' width="48" height="48" src="https://img.icons8.com/fluency/48/bot.png" alt="bot"/></h2>
        
        </div>
       
    <?php
    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["user-input"])) {
        // Get user input
        $user_input = $_POST["user-input"];

        // Prepare data for OpenAI API request
        $openai_api_url = 'https://api.openai.com/v1/chat/completions';
        $data = array(
            "model" => "gpt-3.5-turbo-1106",
            "messages" => array(
                array(
                    "role" => "system",
                    "content" => array(
                        array(
                            "type" => "text",
                            "text" => "what is color of apple\n"
                        )
                    )
                ),
                array(
                    "role" => "user",
                    "content" => array(
                        array(
                            "type" => "text",
                            "text" => $user_input
                        )
                    )
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
            echo '<p>Error:' . curl_error($ch) . '</p>';
        }

        // Close cURL session
        curl_close($ch);

        // Decode JSON response
        $response_array = json_decode($response, true);

        // Display bot's response
        // print_r($response_array);
        // if (isset($response_array['choices'][0]['message']['content'][0]['text'])) {
           
        //     $bot_response = $response_array[choices][0][message][content];
        //     echo '<div class="message bot-message"><strong>ChatGPT:</strong> ' . htmlspecialchars($bot_response) . '</div>';
        // }
        
        $bot_response = $response_array['choices'][0]['message']['content'];
        echo '<div style="width: auto;overflow:hidden;padding:20px;"><pre class="message bot-message" ><strong>Anideaz AI Assistant: </strong> ' . htmlspecialchars($bot_response) . '</pre></div>';
        
    }
    ?>
        
        <!--<form id="message-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">-->
        <!--    <div id="chat-box" >-->
            <!-- Chat messages will be displayed here -->
        <!--</div>-->
        <!--    <input type="text" id="user-input" name="user-input" placeholder="Type your message..." style="width:300px;height:45px;border-redius:15px;border-radius:12px;padding-left:20px">-->
        <!--    <button type="submit" style="width:150px;height:45px;border-radius:12px;backgound-color:orange">Enter</button>-->
        <!--</form>-->
        
        
        <form style='display:flex' id="message-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            
            
    <div id="chat-box">
        
        
        <!-- Chat messages will be displayed here -->
    </div>
    <input   type="text" id="user-input" name="user-input" placeholder="Type your message..." style="background-color: #eee; border: none; padding: 0.6rem; font-size: 0.9rem; width: 43em; margin-right:10px; border-radius: 1rem; color: black; box-shadow: 0 0.4rem #dfd9d9; cursor: pointer;">
    
    <button type="submit" style="background: none; border: none; padding: 0; cursor: pointer;">
        <img width="60" height="60" src="https://img.icons8.com/ios/50/circled-chevron-up.png" alt="Submit">
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
