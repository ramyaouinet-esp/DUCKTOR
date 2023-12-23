<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ducktor Dashboard</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link href="https://fonts.googleapis.com/css?family=Lato:400,400i|Roboto:500" rel="stylesheet">
    <link rel="stylesheet" href="dist/css/style.css">
    <script src="https://unpkg.com/scrollreveal@4.0.0/dist/scrollreveal.min.js"></script>
    <link rel="icon" href="dist/css/duck.png" type="image/png">
</head>
<body class="is-boxed has-animations">
    <div class="body-wrap boxed-container">
        <header class="site-header">
            <div class="container">
                <div class="site-header-inner">
                    <div class="brand header-brand">
                        <h1 class="m-0">
                            <a href="index.php">
                                <img  src="dist/css/duck.png" alt="Description of the image" width="50" height="50">

                                
                            </a>
                        </h1>
                        <nav class="navMenu" style="padding-left:3em;">
      <a href="dash.php">🏠</a>
      <a href="duck.php">💬</a>
      <a href="record.php">🎙️</a>
      <div class="dot"></div>
    </nav>
                    </div>
                </div>
            </div>
        </header>

        <main>
            <section class="hero text-center">
                <div class="container-sm">
                <h1 class="hero-title h2-mobile mt-0 is-revealing">💬 DucktorBot</h1>

                    <div class="hero-inner">
                            <div class="chat-container">
                                <div class="chat-header">DucktorBot</div>
                                <div class="chat-body" id="chatBody">
                                    <!-- Chat messages will be added here dynamically -->
                                </div>
                                <div class="input-container">
                                    <input type="text" id="userInput" placeholder="Type a message...">
                                    <button id="sendBtn" onclick="sendMessage()">📤</button>
                                    <label for="imageInput">
                                        <button id="imageButton" onclick="triggerImageInput()">📷</button>
                                        <input type="file" id="imageInput" accept="image/*" onchange="handleImageUpload()" style="display: none;">
                                    </label>
                                </div>
                                
                            </div>
                       
                    </div>
                    
                    
                    
                </div>

            </section>

         

         

            
        </main>

        <footer class="site-footer text-light">
            <div class="container">
                <div class="site-footer-inner">
                    <div class="brand footer-brand">
                        <a href="#" id="koakLink">
                            <img src="dist/css/duck.png" alt="Description of the image" width="50" height="50">
                        </a>
                        <audio id="koakSound" src="koak.mp3"></audio>
                    </div>
                  
                    <ul class="footer-social-links list-reset">
                       
                    </ul>
                    <div class="footer-copyright">&copy; 2023 ducktor, Made with a lot of ducks</div>
                </div>
            </div>
        </footer>
    </div>
    <script>
        document.getElementById('koakLink').addEventListener('click', function() {
            document.getElementById('koakSound').play();
        });
    </script>
    <script>
        
        async function query(data) {
            const response = await fetch(
                "https://api-inference.huggingface.co/models/ramy21/gptmed4",
                
                {
                    headers: { Authorization: "Bearer " },
                    method: "POST",
                    body: JSON.stringify(data),
                }
            );
            const result = await response.json() ;
            console.log(result);
            return result || 'Unable to transcribe voice message, the model is loadind, please try again';
           
        }
    
        function sendMessage() {
            var userInput = document.getElementById('userInput').value;
            if (userInput.trim() === '') return;
        
            // Append user message to chat
            appendMessage('user', userInput);
            userInput.value = '';
            // Call the API
            query({ "inputs": userInput }).then((response) => {
                var botResponse = response[0].generated_text; // Access the generated_text property
                // Append bot response to chat
                appendMessage('bot', botResponse);
            });
            
        }
        function sendMessage2(userInput) {
            // Call the API
            query({ "inputs": userInput }).then((response) => {
                var botResponse = response[0].generated_text; // Access the generated_text property
                // Append bot response to chat
                appendMessage('bot', botResponse);
            });
        }
        
    
        function appendMessage(sender, message) {
            var chatBody = document.getElementById('chatBody');
            var messageContainer = document.createElement('div');
            messageContainer.className = 'message';
    
            var messageElement = document.createElement('div');
            messageElement.className = sender + '-message';
            messageElement.innerHTML = message;
    
            messageContainer.appendChild(messageElement);
            chatBody.appendChild(messageContainer);
    
            // Scroll to the bottom of the chat
            chatBody.scrollTop = chatBody.scrollHeight;
        }
        function handleImageUpload() {
            var input = document.getElementById('imageInput');
            var file = input.files[0];
    
            if (file) {
                // Append image preview to chat (optional)
                var reader = new FileReader();
                reader.onload = function (e) {
                    var imagePreview = document.createElement('img');
                    imagePreview.src = e.target.result;
                    imagePreview.style.maxWidth = '100%';
                    appendMessage('user', imagePreview.outerHTML);
                };
                reader.readAsDataURL(file);
    
                // Call the API with the image
                queryImage(file);
            }
        }
    
        async function queryImage(imageFile) {
            const data = await imageFile.arrayBuffer();
            const response = await fetch(
                "https://api-inference.huggingface.co/models/youngp5/skin-conditions",
                {
                    headers: { Authorization: "Bearer " },
                    method: "POST",
                    body: data,
                }
            );
            const result = await response.json();
            
            // Extract the first result
            const firstResult = result[0];
        
            // Display the result in the chat
            var botResponse = firstResult.label;
            appendMessage('bot', botResponse);
            sendMessage2("what is "+botResponse);
        }
        function triggerImageInput() {
            document.getElementById('imageInput').click();
        }
    </script>
    <script src="dist/js/main.min.js"></script>
</body>
</html>
