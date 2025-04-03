<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />\
  <link rel="stylesheet" href="login.css">
  <link rel="stylesheet" href="errors.css">
  <title>Title of the document</title>
</head>

<body>
  <?php
  $error = [];
  require_once './vendor/autoload.php';
  require_once './Mail.php';
  require_once "./db.php";
  $ml = $_SESSION['data']['email'];

  if ($_SERVER["REQUEST_METHOD"] == "GET") {


    $to_address  = $ml;
    $randCode = rand(100000, 999999);
    $_SESSION['code'] = $randCode;

    Mail::send($to_address, SUBJECT, "Verification is: {$randCode}");
  }
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    extract($_POST);
    if (strlen($verify) < 6) {
      $error['length'] = "Code should have six digits";
    } else if ($verify == $_SESSION["code"]) {
      $hash = password_hash($_SESSION['data']['password'], PASSWORD_BCRYPT);
      $stmt = $db->prepare("INSERT INTO users (email,password,name,city,district,address,type) VALUES (?,?,?,?,?,?,?)");
      $stmt->execute([$_SESSION['data']['email'], $hash, $_SESSION['data']['name'], $_SESSION['data']['city'], $_SESSION['data']['district'], $_SESSION['data']['address'], $_SESSION['type']]);
    
      header("Location: shopnado.php") ;
      
    }
    else {
      $error["wrong"] = "Wrong code entered";
    }
  
    } 

  ?>

  <div class="main">
    <div class="signup">
      <form action="?" method="post">
        <label>Verification</label>
        <input type="text" class="box" name="verify" placeholder="Enter code" value="<?= isset($verify) ? filter_var($verify, FILTER_SANITIZE_FULL_SPECIAL_CHARS) : "" ?>">
        <button type="submit">Verify</button>
      </form>
    </div>
  </div>
  <ul>
    <?php
    if (!empty($error)) {
      echo "<p class='err'>Errors</p>";
      foreach ($error as $e) {
        if ($e != "") {
          $sanitized_message = filter_var($e, FILTER_SANITIZE_SPECIAL_CHARS);
          echo "<li class='error_msg'>$sanitized_message</li>";
        }
      }
    }
    ?>
  </ul>
</body>

</html>