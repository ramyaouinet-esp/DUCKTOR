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
    <h1 class="hero-title h2-mobile mt-0 is-revealing">⚕ Ducktor Dashboard</h1>

        <div class="hero-inner">

            <!-- Chat Button -->
            <a href="duck.php" class="btn btn-primary is-revealing">
                <span class="btn-text" style=" color: #fff; font-size: 50px;">💬 Chat</span>
            </a>
            <!-- Chat Button Description -->

            <!-- Record Button -->
            <a href="record.php" class="btn btn-secondary is-revealing">
                <span class="btn-text" style=" color: #fff; font-size: 50px;">🎙️ Record</span>
            </a>
            <!-- Record Button Description -->
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
            const result = await response.json();
            console.log(result);
            return result;
           
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
           
        }
       
    </script>
    <script>
    // Initialize ScrollReveal
    ScrollReveal().reveal('.is-revealing', {
        duration: 600,
        delay: 200,
        easing: 'cubic-bezier(0.5, 0, 0, 1)',
        interval: 300,
        origin: 'bottom',
        distance: '20px',
    });
</script>

    <script src="dist/js/main.min.js"></script>
    <script src="https://unpkg.com/scrollreveal@4.0.0/dist/scrollreveal.min.js"></script>

</body>
</html>
