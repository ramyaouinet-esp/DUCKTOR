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
    <h1 class="hero-title h2-mobile mt-0 is-revealing">🎙️ Duckvoice</h1>

        <div class="hero-inner">
            <div class="chat-container">
    <div class="chat-header">Duckvoice</div>
    <div id="voiceChatBody" class="chat-body"></div>
    <div class="input-container">
        <button id="recordBtn" onclick="startRecording()">🎙️</button>
        <button id="stopRecordBtn" onclick="stopRecording()" disabled>⏹️</button>
        <button id="sendVoiceBtn" onclick="sendVoiceMessage()" disabled>📤</button>
    </div>
    <audio id="audioPreview" controls style="display: none;"></audio>
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

        // Previous JavaScript code...

let voiceMediaRecorder;
let voiceAudioChunks = [];

function startRecording() {
    navigator.mediaDevices.getUserMedia({ audio: true })
        .then(stream => {
            voiceMediaRecorder = new MediaRecorder(stream);

            voiceMediaRecorder.ondataavailable = event => {
                if (event.data.size > 0) {
                    voiceAudioChunks.push(event.data);
                }
            };

            voiceMediaRecorder.onstop = () => {
                const voiceAudioBlob = new Blob(voiceAudioChunks, { type: 'audio/wav' });
                const voiceAudioUrl = URL.createObjectURL(voiceAudioBlob);
                document.getElementById('audioPreview').src = voiceAudioUrl;
                document.getElementById('audioPreview').style.display = 'block';
                document.getElementById('sendVoiceBtn').disabled = false;

                // Call the API with the recorded audio
                queryVoiceAudio(voiceAudioBlob);
            };

            voiceMediaRecorder.start();
            document.getElementById('recordBtn').disabled = true;
            document.getElementById('stopRecordBtn').disabled = false;
        })
        .catch(error => {
            console.error('Error accessing microphone:', error);
        });
}

function stopRecording() {
    voiceMediaRecorder.stop();
    document.getElementById('recordBtn').disabled = false;
    document.getElementById('stopRecordBtn').disabled = true;
}

async function queryVoiceAudio(voiceAudioBlob) {
    const data = await voiceAudioBlob.arrayBuffer();
    const response = await fetch(
        "https://api-inference.huggingface.co/models/fractalego/personal-speech-to-text-model",
        {
            headers: { Authorization: "Bearer " },
            method: "POST",
            body: data,
        }
    );
    const result = await response.json();
     const textResult = result.text || 'Unable to transcribe voice message, the model is loadind, please try again';

// Append bot response to chat
appendVoiceMessage('bot', textResult);
}

function sendVoiceMessage() {
    // Clear the recorded audio chunks
    voiceAudioChunks = [];

    // Clear the recorded audio preview
    document.getElementById('audioPreview').style.display = 'none';
    document.getElementById('audioPreview').src = '';
    document.getElementById('sendVoiceBtn').disabled = true;

    const userInput = document.getElementById('userInput').value; // Get the user's text input
    appendVoiceMessage('user', userInput); // Append user's text input to the voice chat

    // Optionally, you can clear the text input field after sending the message
    document.getElementById('userInput').value = '';

    // Add any additional logic for handling the voice message, if needed
}

function appendVoiceMessage(sender, message) {
    var voiceChatBody = document.getElementById('voiceChatBody');
    var messageContainer = document.createElement('div');
    messageContainer.className = 'message';

    var messageElement = document.createElement('div');
    messageElement.className = sender + '-message';
    messageElement.innerHTML = message;

    messageContainer.appendChild(messageElement);

    if (sender === 'bot') {
        var downloadButton = document.createElement('a');
        downloadButton.textContent = '⬇️';
        downloadButton.href = '#'; // Set href to '#' for click event handling
        messageContainer.appendChild(downloadButton);

        downloadButton.addEventListener('click', function () {
            // Create a Blob containing the text
            var blob = new Blob([message], { type: 'text/plain' });
            
            // Create a link element to trigger the download
            var link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = 'transcription.txt';
            
            // Append the link to the document and trigger the click event
            document.body.appendChild(link);
            link.click();
            
            // Remove the link element from the document
            document.body.removeChild(link);
        });
    }

    voiceChatBody.appendChild(messageContainer);

    // Scroll to the bottom of the chat
    voiceChatBody.scrollTop = voiceChatBody.scrollHeight;
}



// ... (remaining JavaScript code) ...

    </script>
  

    <script src="dist/js/main.min.js"></script>
</body>
</html>
