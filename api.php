<?php
// Check if the form is submitted
if (isset($_POST["user-input"])) {
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
                        "text" => ""
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
        "max_tokens" => 4095,
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
   
    echo $response_array['choices'][0]['message']['content'];

    // Display bot's response
    // if (isset($response_array['choices'][0]['message']['content'][0]['text'])) {
    //     $bot_response = $response_array['choices'][0]['message']['content'][0]['text'];
        
    //     // Output the bot's response
    //     echo '<div class="message bot-message"><strong>ChatGPT:</strong> ' . htmlspecialchars($bot_response) . '</div>';
    // } else {
    //     // Handle case where no valid response is found
    //     echo '<p>Error: Unable to retrieve bot response.</p>';
    // }
    
    
} else {
    // Handle case where form is not submitted
    echo '<p>Error: Form submission error.</p>';
}
?>
