<?php
session_start();

// Initialize conversation history if it doesn't exist
if (!isset($_SESSION['conversation_history'])) {
    $_SESSION['conversation_history'] = array();
}

// Handle clear history request
if (isset($_POST['clear_history'])) {
    unset($_SESSION['conversation_history']);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>

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
        @import url('https://fonts.googleapis.com/css2?family=Chakra+Petch:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap');
            body {
                font-family: 'Chakra Petch';
                margin: 0;
                padding: 0;
                display: flex;
                flex-direction: column;
                height: 100vh;
                transition: background-color 0.3s ease, color 0.3s ease;
            }
    
            body.light-mode {
                background-color: #f0f0f0;
                color: #333;
            }
    
            body.dark-mode {
                background-color: #121212;
                color: #f0f0f0;
            }
            .h3{
                background-color:#F4F4F4;
                padding:25px;
                border-radius:12px;
                box-shadow: rgba(0, 0, 0, 0.12) 0px 1px 3px, rgba(0, 0, 0, 0.24) 0px 1px 2px;
                word-wrap: break-word;
                white-space: normal;
                border-radius: 10px;
                width:40%;
                float:right;
               
            }
    
            header {
               background-image: linear-gradient(41deg, rgba(107, 107, 107, 0.04) 0%, rgba(107, 107, 107, 0.04) 8%,rgba(31, 31, 31, 0.04) 8%, rgba(31, 31, 31, 0.04) 100%),linear-gradient(9deg, rgba(228, 228, 228, 0.04) 0%, rgba(228, 228, 228, 0.04) 62%,rgba(54, 54, 54, 0.04) 62%, rgba(54, 54, 54, 0.04) 100%),linear-gradient(124deg, rgba(18, 18, 18, 0.04) 0%, rgba(18, 18, 18, 0.04) 37%,rgba(233, 233, 233, 0.04) 37%, rgba(233, 233, 233, 0.04) 100%),linear-gradient(253deg, rgba(201, 201, 201, 0.04) 0%, rgba(201, 201, 201, 0.04) 55%,rgba(47, 47, 47, 0.04) 55%, rgba(47, 47, 47, 0.04) 100%),linear-gradient(270deg, rgba(172, 172, 172, 0.04) 0%, rgba(172, 172, 172, 0.04) 33%,rgba(26, 26, 26, 0.04) 33%, rgba(26, 26, 26, 0.04) 100%),linear-gradient(64deg, rgba(11, 11, 11, 0.04) 0%, rgba(11, 11, 11, 0.04) 38%,rgba(87, 87, 87, 0.04) 38%, rgba(87, 87, 87, 0.04) 100%),linear-gradient(347deg, rgba(199, 199, 199, 0.04) 0%, rgba(199, 199, 199, 0.04) 69%,rgba(4, 4, 4, 0.04) 69%, rgba(4, 4, 4, 0.04) 100%),linear-gradient(313deg, rgba(36, 36, 36, 0.04) 0%, rgba(36, 36, 36, 0.04) 20%,rgba(91, 91, 91, 0.04) 20%, rgba(91, 91, 91, 0.04) 100%),linear-gradient(90deg, rgb(10, 17, 72),rgb(35, 148, 228));
                /*background-image: linear-gradient(90deg, rgba(6, 6, 6, 0.01) 0%, rgba(6, 6, 6, 0.01) 1%,rgba(131, 131, 131, 0.01) 1%, rgba(131, 131, 131, 0.01) 14%,rgba(250, 250, 250, 0.01) 14%, rgba(250, 250, 250, 0.01) 26%,rgba(30, 30, 30, 0.01) 26%, rgba(30, 30, 30, 0.01) 62%,rgba(117, 117, 117, 0.01) 62%, rgba(117, 117, 117, 0.01) 66%,rgba(248, 248, 248, 0.01) 66%, rgba(248, 248, 248, 0.01) 76%,rgba(39, 39, 39, 0.01) 76%, rgba(39, 39, 39, 0.01) 100%),linear-gradient(90deg, rgba(57, 57, 57, 0.06) 0%, rgba(57, 57, 57, 0.06) 4%,rgba(227, 227, 227, 0.06) 4%, rgba(227, 227, 227, 0.06) 26%,rgba(67, 67, 67, 0.06) 26%, rgba(67, 67, 67, 0.06) 27%,rgba(126, 126, 126, 0.06) 27%, rgba(126, 126, 126, 0.06) 39%,rgba(103, 103, 103, 0.06) 39%, rgba(103, 103, 103, 0.06) 72%,rgba(16, 16, 16, 0.06) 72%, rgba(16, 16, 16, 0.06) 76%,rgba(21, 21, 21, 0.06) 76%, rgba(21, 21, 21, 0.06) 88%,rgba(69, 69, 69, 0.06) 88%, rgba(69, 69, 69, 0.06) 100%),linear-gradient(90deg, rgba(156, 156, 156, 0.05) 0%, rgba(156, 156, 156, 0.05) 12%,rgba(54, 54, 54, 0.05) 12%, rgba(54, 54, 54, 0.05) 48%,rgba(169, 169, 169, 0.05) 48%, rgba(169, 169, 169, 0.05) 59%,rgba(104, 104, 104, 0.05) 59%, rgba(104, 104, 104, 0.05) 62%,rgba(165, 165, 165, 0.05) 62%, rgba(165, 165, 165, 0.05) 68%,rgba(124, 124, 124, 0.05) 68%, rgba(124, 124, 124, 0.05) 77%,rgba(189, 189, 189, 0.05) 77%, rgba(189, 189, 189, 0.05) 85%,rgba(173, 173, 173, 0.05) 85%, rgba(173, 173, 173, 0.05) 100%),linear-gradient(90deg, rgba(182, 182, 182, 0.07) 0%, rgba(182, 182, 182, 0.07) 22%,rgba(122, 122, 122, 0.07) 22%, rgba(122, 122, 122, 0.07) 28%,rgba(62, 62, 62, 0.07) 28%, rgba(62, 62, 62, 0.07) 44%,rgba(89, 89, 89, 0.07) 44%, rgba(89, 89, 89, 0.07) 61%,rgba(110, 110, 110, 0.07) 61%, rgba(110, 110, 110, 0.07) 83%,rgba(185, 185, 185, 0.07) 83%, rgba(185, 185, 185, 0.07) 86%,rgba(192, 192, 192, 0.07) 86%, rgba(192, 192, 192, 0.07) 100%),linear-gradient(90deg, rgba(8, 8, 8, 0.06) 0%, rgba(8, 8, 8, 0.06) 54%,rgba(48, 48, 48, 0.06) 54%, rgba(48, 48, 48, 0.06) 57%,rgba(245, 245, 245, 0.06) 57%, rgba(245, 245, 245, 0.06) 86%,rgba(12, 12, 12, 0.06) 86%, rgba(12, 12, 12, 0.06) 94%,rgba(225, 225, 225, 0.06) 94%, rgba(225, 225, 225, 0.06) 100%),linear-gradient(90deg, rgb(53, 169, 225) 0%,rgb(1, 145, 219) 80%,rgb(26, 221, 247) 100%);*/
                color: white;
                text-align: center;
                padding: 35px;
                font-size: 25px;
                
            }
    
    
            .chat-container {
                flex: 1;
                display: flex;
                flex-direction: column;
                padding: 20px;
                background-color: white;
                overflow-y: auto;
                transition: background-color 0.3s ease, color 0.3s ease;
                margin-bottom:70px;
                
               
            }
    
            .dark-mode .chat-container {
                background-color: #1e1e1e;
                color: white;
            }
    
            .message {
                margin: 10px 0;
                padding: 10px;
                border-radius: 10px;
                max-width: 70%;
                transition: background-color 0.3s ease, color 0.3s ease;
            }
    
            .message.user {
                background-color: #d4edda;
                align-self: flex-end;
            }
    
          
    
            .message.ai {
                background-color: #f8d7da;
                align-self: flex-start;
            }
    
           
    
            footer {
                background-color: #f0f0f0;
                padding: 15px;
                display: flex;
                border-top: 1px solid #ccc;
                transition: background-color 0.3s ease, color 0.3s ease;
            }
    
          
    
            footer input[type="text"] {
                flex: 1;
                padding: 10px;
                border: 1px solid #ccc;
                border-radius: 25px;
                transition: background-color 0.3s ease, color 0.3s ease;
                font-size:19px;
            }
    
            .dark-mode footer input[type="text"] {
                background-color: #555;
                color: white;
                border-color: #555;
                
            }
    
            .button {
                background-color: #3D9BC9;
                color: white;
                border: none;
                font-size:18px;
                padding: 15px 26px;
                margin-left: 10px;
                border-radius: 25px;
                cursor: pointer;
                font-weight:700;
                transition: background-color 0.3s ease, color 0.3s ease;
                
            }
    
            .dark-mode .button {
                background-color: #4CAF50;
                color: white;
            }
    
            .button:hover {
                background-color: #3997c5;
            }
    
            .footer button:hover {
                background-color: #45a049;
            }
    
            pre {
                box-shadow: rgba(0, 0, 0, 0.12) 0px 1px 3px, rgba(0, 0, 0, 0.24) 0px 1px 2px;
                word-wrap: break-word;
                white-space: normal;
                border-radius: 10px;
                background-color: #282C34;
                color: white;
                padding: 15px;
                word-spacing: 2px;
                font-size: 16px;
                transition: background-color 0.3s ease, color 0.3s ease;
                font-family: 'Chakra Petch';
              
            }
            
            
            @media only screen and (max-width: 600px) {
              footer {
                  width:100%;
                  position:fixed;
                  bottom:0;
              }
              
               .button {
                font-size:12px;
                padding: 10px;
                margin-left: 5px;
                border-radius: 11px;
                cursor: pointer;
                font-weight:700;
                transition: background-color 0.3s ease, color 0.3s ease;
                
            }
            }

 </style>
</head>

<body>

    <header>
        Anideaz AI Assistant
    </header>

    <div class="chat-container">
        <div id="chat-box">
            <?php
            // Display conversation history
            foreach ($_SESSION['conversation_history'] as $message) {
                if ($message['role'] == 'user') {
                    echo '<h4 class="h3">' . $message['content'] . '</h4>';
                } else {
                    echo '<pre style="width:70%;overflow:hidden;position:relative"><img width="24" height="24" src="https://img.icons8.com/arcade/64/wind-rose.png" alt="wind-rose"/> ' . $message['content'] . '</pre>';
                }
            }

            // Handle new user input
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["user-input"])) {
                $user_input = htmlspecialchars($_POST["user-input"]);

                // Add user input to history
                $_SESSION['conversation_history'][] = array('role' => 'user', 'content' => $user_input);

                // Display user's prompt
                echo '<h4 class="h3">' . $user_input . '</h4>';

                // Prepare data for OpenAI API request
                $openai_api_url = 'https://api.openai.com/v1/chat/completions';
                $data = array(
                    "model" => "gpt-3.5-turbo",
                    "messages" => array_merge(
                        array(array(
                            "role" => "system",
                            "content" => "You are a helpful assistant."
                        )),
                        $_SESSION['conversation_history']
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
                    echo '<pre style="width:70%;overflow:hidden;position:relative"><img width="24" height="24" src="https://img.icons8.com/arcade/64/wind-rose.png" alt="wind-rose"/> ' . $bot_response . '</pre>';

                    // Add bot response to history
                    $_SESSION['conversation_history'][] = array('role' => 'assistant', 'content' => $bot_response);
                }
            }
            ?>
        </div>
    </div>

    <footer>
        <form style="width:90%; display:flex; justify-content:center" class="form-container" id="message-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <input type="text" id="user-input" name="user-input" placeholder="Type your message..." required>
            <button id="submit-button" class="button" type="submit">Send</button>
        </form>
        <!--<form method="post" action="">-->
        <!--    <button type="submit" class="button"  name="clear_history">Clear</button>-->
        <!--</form>-->
    </footer>



</body>

</html>