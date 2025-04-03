<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="register.css">
  <link rel="stylesheet" href="errors.css">
  <title>Document</title>
</head>
<?php
$type=$_GET['type'];
$heading=$type=="market"?"Market":"Consumer";
$nm=$type=="market"?"Market Name":"Full Name";
require "./db.php";
if (!empty($_POST)) {
  extract($_POST);
  $error = [];
  if($name==""){
    $error["name"]="Please fill the name field";
  }
  if($email == ""){
    $error["email"]="Please fill the email field";
  }
  if($password == ""){
    $error["password"] ="Please fill the password field";
  }
  if($confirm == ""){
    $error["confirm"] ="Please fill the confirm field";
  }
  if($city == ""){
    $error["city"] = "Please fill the city field";
  }
  if($district == ""){
    $error["district"] = "Please fill the district field" ;
  }
  if($address == ""){
    $error["address"] = "Please fill the address field";
  }
  if ($password!="" && strlen($password) < 6) {
    $error["pass"] = "Please enter more than 6 characters for your password";
  }
  if ($password!="" && $password != $confirm) {
    $error['confirmation'] = "Your passwords do not match";
  }
  if(empty($error)){
    $_SESSION['data']=$_POST;
    $_SESSION['type']=$type;
    header("Location:verification.php");
    var_dump($_SESSION['data']);
  }
}

?>

<body>
  <div class="container">
    <div class="title">Registration |<span id="ty"><?=$heading?></span></div>
    <div class="content">
      <form method="post">
        <div class="user-details">
          <div class="input-box">
            <span class="details"><?=$nm?></span>
            <input type="text" placeholder="Enter Market name" name="name" value="<?= isset($name) ? filter_var($name, FILTER_SANITIZE_FULL_SPECIAL_CHARS) : "" ?>">
          </div>
          <div class="input-box">
            <span class="details">Email</span>
            <input type="text" placeholder="Enter your Email" name="email" value="<?= isset($email) ? filter_var($email, FILTER_SANITIZE_FULL_SPECIAL_CHARS) : "" ?>">
          </div>
          <div class="input-box">
            <span class="details">Password</span>
            <input type="password" placeholder="Enter your password" name="password" value="<?= isset($password) ? filter_var($password, FILTER_SANITIZE_FULL_SPECIAL_CHARS) : "" ?>">
          </div>
          <div class="input-box">
            <span class="details">Confirm Password</span>
            <input type="password" placeholder="Confirm your password" name="confirm" value="<?= isset($confirm) ? filter_var($confirm, FILTER_SANITIZE_FULL_SPECIAL_CHARS) : "" ?>">
          </div>
          <div class="input-box">
            <span class="details">City</span>
            <input type="text" placeholder="Enter your city" name="city" value="<?= isset($city) ? filter_var($city, FILTER_SANITIZE_FULL_SPECIAL_CHARS) : "" ?>">
          </div>
          <div class="input-box">
            <span class="details">District</span>
            <input type="text" placeholder="Enter your district" name="district" value="<?= isset($district) ? filter_var($district, FILTER_SANITIZE_FULL_SPECIAL_CHARS) : "" ?>">
          </div>
          <div class="input-box">
            <span class="details">Address</span>
            <input type="text" placeholder="Enter your address" name="address" value="<?= isset($address) ? filter_var($address, FILTER_SANITIZE_FULL_SPECIAL_CHARS) : "" ?>">
          </div>
        </div>

        <div class="button">
          <button id="Register" type="submit">Register</button>
          <!-- <a href="./index.html"> -->
          <!-- </a> -->
        </div>
      </form>
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