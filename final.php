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

// Handle new user input
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["user-input"])) {
    $user_input = htmlspecialchars($_POST["user-input"]);

    // Add user input to history
    $_SESSION['conversation_history'][] = array('role' => 'user', 'content' => $user_input);

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

    // Process and store the bot's response
    if (isset($response_array['choices'][0]['message']['content'])) {
        $bot_response = htmlspecialchars($response_array['choices'][0]['message']['content']);
        
        // Add bot response to history
        $_SESSION['conversation_history'][] = array('role' => 'assistant', 'content' => $bot_response);
    }

    // Redirect to the same page to avoid reprocessing on refresh
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
    <link rel="stylesheet" type="text/css" href="./css/style.css">
</head>

<body>

    <header>
        Anideaz AI Assistant
        <button id="toggle-mode" style='float:right;' class="toggle-mode-btn">
            <img width="24" height="24" src="https://img.icons8.com/glyph-neue/64/fog-night--v1.png" alt="fog-night--v1"/>
        </button>
    </header>

    <div class="chat-container">
        <div id="chat-box">
            <?php
            // Display conversation history
            foreach ($_SESSION['conversation_history'] as $message) {
                if ($message['role'] == 'user') {
                    echo '<h4 class="h3">' . $message['content'] . '</h4>';
                } else {
                    echo '<pre style="width:60%;overflow:hidden;position:relative"><img class="icon" width="20" height="20" src="https://img.icons8.com/fluency/48/double-right.png" alt="double-right"/> ';
                    echo "<span class='bot-response'>" . $message['content'] . "</span></pre>";
                }
            }
            ?>
        </div>
    </div>
    
    <footer>
        <form style="width:100%; display:flex; justify-content:center" class="form-container" id="message-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <input type="text" id="user-input" name="user-input" placeholder="Type your message..." required>
            <button id="submit-button" type="submit"><img width="36" height="36" src="https://img.icons8.com/papercut/60/sent.png" alt="sent"/></button>
        </form>
        <form method="post" action="">
            <button id="submit-button" type="submit" name="clear_history"><img width="34" height="34" src="https://img.icons8.com/3d-fluency/94/broom.png" alt="broom"/></button>
        </form>
    </footer>

<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        // Dark mode toggle functionality
        const toggleButton = document.getElementById('toggle-mode');
        const currentMode = localStorage.getItem('theme') || 'light';

        if (currentMode === 'dark') {
            document.body.classList.add('dark-mode');
        }

        toggleButton.addEventListener('click', () => {
            document.body.classList.toggle('dark-mode');
            const isDarkMode = document.body.classList.contains('dark-mode');
            localStorage.setItem('theme', isDarkMode ? 'dark' : 'light');
        });

        // Typewriter effect
        const typeWriter = (text, element, speed = 10) => {
            let i = 0;
            const interval = setInterval(() => {
                if (i < text.length) {
                    element.innerHTML += text.charAt(i);
                    i++;
                } else {
                    clearInterval(interval);
                }
            }, speed);
        };

        // Apply typewriter effect only to the most recent bot response
        const botResponses = document.querySelectorAll('.bot-response');
        if (botResponses.length > 0) {
            const latestResponse = botResponses[botResponses.length - 1];
            const text = latestResponse.textContent;
            latestResponse.textContent = '';
            typeWriter(text, latestResponse);
        }
    });
</script>

</body>
</html>
