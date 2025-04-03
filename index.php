<!DOCTYPE html>
<html>
  <head>
    <title>Register</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link
      href="https://fonts.googleapis.com/css2?family=Jost:wght@500&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="./login.css" />
    <link rel="stylesheet" href="./errors.css">
  </head>
  <?php
  require "./db.php";
  if(isset($_GET["destroy"]))
    session_destroy();
  
  $error = [];
  if(!empty($_POST)){
    extract($_POST);
    if($email == ""){
      $error["email"]="Please fill the email field";
    }
    if($password == ""){
      $error["password"] ="Please fill the password field";
    }
    if($password!="" && $email!=""){
      if(checkUser($email,$password,$user)){


        // login as $user
     // login as $user
     $_SESSION["user"] = $user ; 
     if($_SESSION['user']["type"] == "market") {
       header("Location: market.php") ;
     } else {
      header("Location: ydk.php") ;
     }
   }
      }
      else{
        $error["match"] ="Login Failed";
      }   
    }    
  
  // Remember-me part
  if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_COOKIE["access_token"])) {
    $user = getUserByToken($_COOKIE["access_token"]) ;
    if ( $user ) {
        $_SESSION["user"] = $user ; // auto login
        if($_SESSION["type"] == "market") {
          header("Location: market.php") ;
        } else {
          header("Location: ydk.php") ;
        }
        exit ; 
    }
 }
  ?>
  <body>
    <div class="main">
      <div class="signup">
        <form action="?" method="post">
          <label>Login</label>
          <input type="email" class="box" name="email" placeholder="Email" value="<?= isset($email)?filter_var($email, FILTER_SANITIZE_FULL_SPECIAL_CHARS) : "" ?>" >
          <input type="password" class="box" name="password" placeholder="Password" value="<?= isset($password) ? filter_var($password, FILTER_SANITIZE_FULL_SPECIAL_CHARS) : "" ?>" >
          <button type="submit">Login</button>
        </form>
        <a href="./register.php?type=market"><button>Register Market</button></a>
        <a href="./register.php?type=consumer"><button>Register Consumer</button></a>
      </div>
    </div>
    <ul>
    <?php
    if (!empty($error)) {
      echo "<p class='err'>Errors</p>";
      foreach ($error as $e) {
        if($e!=""){
          $sanitized_message = filter_var($e, FILTER_SANITIZE_SPECIAL_CHARS);
          echo "<li class='error_msg'>$sanitized_message</li>";
        }
      }
      
    }
    ?>
  </ul>
  </body>
</html>
