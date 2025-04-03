<?php
    require "./db.php";
//  var_dump($_SESSION);
    if($_SERVER["REQUEST_METHOD"] == "GET" || $_SERVER["REQUEST_METHOD"] == "POST") {
      if (!isset($_SESSION["user"])) {
        header("Location: ./index.php");
      }
    }
    $stmt = $db->prepare("select * from users where id = ?");
    $stmt->execute([$_SESSION["user"]["id"]]);
    $user = $stmt->fetch();
    $id = $_SESSION["user"]["id"];
    $errors = [];
    $succesMsg = "";

   
   if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if($_POST["profileFormToken"] != $_SESSION["user"]["profileFormToken"]) {
            header ("Location: ./index.php");
        } 
        extract($_POST); #to get the attributes like name, city, district...
        if(!empty($currPassword)) {
            if (!password_verify($currPassword, $user["password"])) {
                $errors[] = "Current password in entered wrongly";
            }
            else if(empty($newPassword1) || empty($newPassword2)) {
                $errors[] = "Fill Both new password fields";
            } else if ($newPassword1 != $newPassword2) {
                $errors[] = "Passwords does not match";
            } else {
                try {
                    $stmt = $db->prepare("update users set name = ?, city = ?, district = ?, address = ?, password = ? where id = ?");
                    $stmt->execute([$name, $city, $district, $address, password_hash($newPassword1, PASSWORD_BCRYPT), $id]);
                    $succesMsg = "Your profile successfully updated";

                    if($user["type"] == "market") {
                        header("Location: ./market.php");
    
                    } else if ($user["type"] == "consumer") {
                        header("Location: ./ydk.php");
                    }

                } catch (PDOException $e) {
                    $errors[] = "Profile could not be updated";
                }

            }
            
        } else {
           
            #firstly update the session
            // var_dump($name);
            $_SESSION["user"]["name"] = $name;
            $_SESSION["user"]["city"] = $city;
            $_SESSION["user"]["district"] = $district;
            $_SESSION["user"]["address"] = $address;
            
            try {
                $stmt = $db->prepare("update users set name = ?, city = ?, district = ?, address = ? where id = ?");
                $stmt->execute([$name, $city, $district, $address, $id]);
                $succesMsg = "Your profile successfully updated";

                if($user["type"] == "market") {
                    header("Location: ./market.php");

                } else if ($user["type"] == "consumer") {
                    header("Location: ./ydk.php");
                }

            } catch (PDOException $e) {
                $errors[] = "Profile could not be updated";
            }
        } 
    } else {
        $_SESSION["user"]["profileFormToken"] = bin2hex(openssl_random_pseudo_bytes(16));

    }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profie</title>
    <link rel="stylesheet" href="profile.css">
    <link flex href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <link rel='stylesheet' href='https://unicons.iconscout.com/release/v2.1.9/css/unicons.css'>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
<form action="" method="POST">

    <div class="d-flex flex-column justify-content-center w-100 h-100">

	<div class="d-flex flex-column justify-content-center align-items-center">
		</a>
	</div>
    <div class="erdem"></div>
    <div class="container light-style flex-grow-1 container-p-y" style="min-height: 800px;">
        <div class="card overflow-hidden">
            <div class="row no-gutters row-bordered row-border-light">
                <div class="col-md-3 pt-0 left-column">
                    <div class="list-group list-group-flush account-settings-links">
                        <a href="./<?= $user["type"] == "market" ? "market" : "ydk" ?>.php" class="list-group-item list-group-item-action general" style="font-size:32px; padding: 0px; padding-left:24px"><i class="bx bx-arrow-to-left"></i></a>
                        <a class="list-group-item list-group-item-action general active">General</a>
                        <a class="list-group-item list-group-item-action password ">Change password</a>

                    </div>
                </div>
                <div class="col-md-9 right-column">
                    <div class="tab-content">
                        <div class="tab-pane general-div active show" id="account-general">
                            <div class="card-body media align-items-center">
                                <img src="./images/tornado.png" alt
                                    class="d-block ui-w-80">
                                <div class="media-body ml-4">
                                    <label class="btn btn-outline-primary">
                                        Upload new photo
                                        <input type="file" class="account-settings-fileinput">
                                    </label> &nbsp;
                                    <!-- <button type="button" class="btn btn-default md-btn-flat">Reset</button> -->
                                </div>
                            </div>
                            <hr class="border-light m-0">
                            <div class="card-body">
                                <div class="form-group">
                                    <label class="form-label" style="margin-bottom:4px">Name</label>
                                    <input type="text" class="edit-input" value="<?= isset($_POST["name"]) ? htmlspecialchars($_POST["name"]) : htmlspecialchars($user["name"]) ?>" name="name" required>
                                </div>
                                <!-- <div class="form-group">
                                    <label class="form-label" style="margin-bottom:4px">Email</label>
                                    <input type="text" class="edit-input" value="<?= isset($user["email"]) ? htmlspecialchars($user["email"]) : htmlspecialchars($email) ?>" name="email" required>
                                </div> -->
                                <div class="form-group">
                                    <label class="form-label" style="margin-bottom:4px">City</label>
                                    <input type="text" class="edit-input" value="<?= isset($_POST["city"]) ? htmlspecialchars($_POST["city"]) : htmlspecialchars($user["city"])  ?>" name="city" required>

                                </div>
                                <div class="form-group">
                                    <label class="form-label" style="margin-bottom:4px">District</label>
                                    <input type="text" class="edit-input" value="<?= isset($_POST["district"]) ? htmlspecialchars($_POST["district"]) : htmlspecialchars($user["district"])  ?>" name="district" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" style="margin-bottom:4px">Address</label>
                                    <input type="text" class="edit-input" value="<?= isset($_POST["address"]) ? htmlspecialchars($_POST["address"]) : htmlspecialchars($user["address"])  ?>" name="address" required>
                                </div>
                                <input type="hidden" name="profileFormToken" value="<?=$_SESSION["user"]["profileFormToken"]?>">

                                <div>
                                    <?php
                                        foreach($errors as $err) {
                                            echo "<p style='color:red; font-size:12px; font-style:italic'>{$err}</p>";
                                        }
                                    ?>
                                    <?php
                                        if(!empty($succesMsg)) {
                                            echo "<p style='color:green; font-size:12px; font-style:italic'>{$succesMsg}</p>";
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane password-div show" id="account-change-password">
                            <div class="card-body pb-2">
                                <div class="form-group">
                                    <label class="form-label">Current password</label>
                                    <input minlength="6" type="password" class="edit-input input-password" name="currPassword">
                                    <i class="password_toggle uil uil-eye-slash"></i>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">New password</label>
                                    <input minlength="6" type="password" class="edit-input input-password" name="newPassword1">
                                    <i class="password_toggle uil uil-eye-slash"></i>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Repeat new password</label>
                                    <input minlength="6" type="password" class="edit-input input-password" name="newPassword2">
                                    <i class="password_toggle uil uil-eye-slash"></i>
                                </div>
                            </div>
                        </div>
                                      
                    </div>
                    <div class="text-right mt-3">

                        <button type="submit" class="btn btn-primary"><span>Save changes</span></button>&nbsp;
                        <!-- <button type="button" class="btn btn-default">Cancel</button> -->
                    </div>

                </div>

            </div>
        </div>

    </div>
    </form>

    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script>
        generalBoxLink = $(".general");
        passwordBoxLink = $(".password");
        generalDiv = $(".general-div");
        passwordDiv = $(".password-div");


        generalBoxLink.click(function() {
            $("a.active").removeClass("active");
            generalBoxLink.addClass("active");

            $("div.active").removeClass("active");
            generalDiv.addClass("active");
        });
        passwordBoxLink.click(function() {
            $("a.active").removeClass("active");
            passwordBoxLink.addClass("active");

            $("div.active").removeClass("active");
            passwordDiv.addClass("active");
        });


        const toggleList = document.querySelectorAll(".password_toggle"),
		inputList = document.querySelectorAll(".input-password");

		for (let i=0; i<toggleList.length; i++) {
			let toggle = toggleList[i];
			let input = inputList[i];

			toggle.addEventListener("click", (e) =>{
			if(input.type ==="password"){
			input.type = "text";
			toggle.classList.replace("uil-eye-slash", "uil-eye");
			}else{
			input.type = "password";
			toggle.classList.replace("uil-eye", "uil-eye-slash");

			}
		})
		}
    </script>
</body>

</html>