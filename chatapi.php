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