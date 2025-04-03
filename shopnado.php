<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        body {
  margin: 0;
  padding: 0;
  font-family: sans-serif;
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
  background: linear-gradient(to right, #f2f2f2, #bcbcbc); /* Change gradient colors here */
  animation: background-shift 10s infinite ease-in-out;
}

@keyframes background-shift {
  0% {
    background-position: 0 0;
  }
  50% {
    background-position: 100% 0;
  }
  100% {
    background-position: 0 0;
  }
}

.container {
  background: rgba(0, 0, 0, 0.5);
  padding: 30px;
  border-radius: 10px;
  animation: entrance 0.5s ease-in-out forwards;
}

@keyframes entrance {
  0% {
    opacity: 0;
    transform: scale(0.8);
  }
  100% {
    opacity: 1;
    transform: scale(1);
  }
}

h1 {
  color: white;
  font-size: 3em;
  text-align: center;
}

p {
  color: white;
  font-size: 1.2em;
  text-align: center;
}
.tornado-container {
  width: 200px; /* Adjust width as needed */
  overflow: hidden;
}

.tornado-container img {
  animation: move-tornado 5s linear forwards;
}

@keyframes move-tornado {
  0% {
    transform: translateX(-100%); /* Start off-screen to the left */
  }
  100% {
    transform: translateX(0); /* End at the normal position */
  }
}
    </style>
</head>
<body>
<div class="container">
  
    <h1>Welcome!</h1>
    <img src="./images/tornado.png" alt="Tornado">
    <h1>You are in <span style='font-style: italic'>Shopnado</span></h1>
    <p>We are saving your data, then you can login to the system</p>
    <div>
    <img src="./images/wait.gif" alt="" style='width:15%;margin:auto'></div>
    </div>
  <script>
    setTimeout(function() {
      window.location.href = "index.php"; // Replace "main.html" with your actual main page
    }, 5000); // 5000 milliseconds = 5 seconds
  </script>
</body>
</html>