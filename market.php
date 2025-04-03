<?php
// session_start();
  require "./db.php";

  if($_SERVER["REQUEST_METHOD"] == "GET" || $_SERVER["REQUEST_METHOD"] == "POST") {
    if(!isset($_SESSION["user"])) {
      header("Location: ./index.php");
    }else {
        if($_SESSION["user"]["type"] != "market") {
           header("Location: ./index.php");
        }
    }
  }

  


  $market_id = $_SESSION["user"]["id"]; // needed data to trace the progress
  $currTitle = "";
  $operationType = "";
  $updateErrors = [];  
  $addErrors = [];


  if($_SERVER["REQUEST_METHOD"] == "GET") {  // GET - PROCESS
    $_SESSION["user"]["addFormToken"] = bin2hex(openssl_random_pseudo_bytes(16));


    if(isset($_GET["edit"])) {
      $currTitle = htmlspecialchars($_GET["edit"]); 
      $operationType = "edit";
    } else if (isset($_GET["del"])) {
      $currTitle =  htmlspecialchars($_GET["del"]);
      $operationType = "delete";
      if(isset($_GET["approved"])) {
        try {
          $stmt = $db->prepare("delete from products where title = ? and market_id = ?");
          $stmt->execute([$_GET["del"], $market_id ]);
          $ordered = isset($_GET["order"]) ? "&order=all" : "";
          if(empty($ordered)) {
            header("Location: market.php?deleted=on");
          } else {
            header("Location: market.php?deleted=on&order=all");
          }        
        }
        catch (PDOException $e) {
          echo "Set username and password in 'db.php' appropriately" ;
          // error toast notificitations
          exit;
        }
      }
    } else if (isset($_GET["add"])) {
      $operationType = "add";
    }
  } else if ($_SERVER["REQUEST_METHOD"] == "POST") {  // POST - PROCESS
    if($_POST["addFormToken"] != $_SESSION["user"]["addFormToken"]) {
      header ("Location: ./index.php");
    }

    if(isset($_POST["old_title"])) {
      try {
        /*if($status){  
          echo "File deleted successfully";    
        }else{  
          echo "Sorry!";    
        }*/

        $imageCode = uploadImage("image");

        if(count(explode(" ", $imageCode)) > 2) {
          $updateErrors[] = $imageCode;
          $currTitle = htmlspecialchars($_POST["title"]);
        } else {
          if(empty($imageCode)) {
            $imageCode = htmlspecialchars($_POST['old_image']);
          } else {
            try {
              $status=@unlink("./images/{$_POST['old_image']}");    
            } catch (PDOException $fileNotFounded) {
              var_dump("File Not Founded");
            }
          }
  
          $stmt = $db->prepare("update products set title = ?, stock = ?, expiration_date = ?, normal_price = ?, discounted_price = ?, image= ? where market_id = ? and title = ?");
          $stmt->execute([$_POST["title"], $_POST["stock"], $_POST["date"], $_POST["price"], $_POST["discounted_price"], $imageCode, $market_id, $_POST["old_title"]]);
          
        }

}
      catch (PDOException $e) {
        $updateErrors[] = "Duplicate Products" ;
        // error toast notificitations
      }
    } else { // ADD NEW PRODUCT
      try {
        if(empty($_POST["title"])) {
          $addErrors[] = "Title cannot be empty";
        }
        if (empty($_POST["stock"]) || $_POST["stock"] < 0) {
          $addErrors[] = "Stock cannot be empty, or less than 0";
        }
        if (empty($_POST["price"]) || $_POST["price"] < 0) {
          $addErrors[] = "Price cannot be empty, or less than 0";
        } 
        if (empty($_POST["discounted_price"]) || $_POST["discounted_price"] < 0) {
          $addErrors[] = "Discounted Price cannot be empty, or less than 0";
        }
        if (empty($_POST["date"])) {
          $addErrors[] = "Expiration Date cannot be empty";
        }
         if(count($addErrors) == 0) {
          $imageCode = uploadImage("image");

          if(count(explode(" ", $imageCode)) > 2) {
            $addErrors[] = $imageCode;
            //$currTitle = $_POST["title"];
          } else {

            $query = "INSERT INTO `products` (`market_id`, `title`, `stock`, `normal_price`, `discounted_price`, `expiration_date`, `image`) VALUES (?, ?, ? , ?, ?, ?, ?);";
            $stmt = $db->prepare($query);
            $stmt->execute([$market_id, $_POST["title"], $_POST["stock"], $_POST["price"], $_POST["discounted_price"], $_POST["date"], $imageCode]);
            $operationType = "add-completed";
          }


        }

      }
      catch (PDOException $e) {
        $addErrors[] = "Duplicate Recors tried to created";
        // error toast notificitations
      }
    }

  }

  if(isset($_GET["order"]) && htmlspecialchars($_GET["order"]) == "all") {
    $stmt = $db->prepare("select * from products where market_id = ?");
  } else {
    $stmt = $db->prepare("select * from products where market_id = ? and expiration_date < CURRENT_DATE");
  }
  $stmt->execute([$market_id]);
  $res = $stmt->fetchAll(pdo::FETCH_ASSOC);

  $stmt = $db->prepare("select * from users where id = ?");
  $stmt->execute([$market_id]);
  $profile = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title></title>
    <link rel="stylesheet" href="market.css" />
    <link flex href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <!-- <script src="script.js" defer></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  </head>
  <body>
    <div class="ov <?php if (isset($_GET["add"]) || isset($_GET["edit"]) || $_GET["del"]) {echo "active";} else {echo "no";} ?>"><p>Erdem Atila</p></div>
  <!-- <div class="erdem"></div> -->
    <div class="sidebar">
      <div class="logo-details">
        <!-- <i class='bx bxl-c-plus-plus icon'>
        </i> -->
          <img class="icon" src="./images/tornado.png" alt="">

          <div class="logo_name">Shopnado</div>
          <i class='bx bx-menu' id="btn" ></i>
      </div>
      <ul class="nav-list">
        <!-- <li>
            <i class='bx bx-search' ></i>
           <input type="text" placeholder="Search...">
           <span class="tooltip">Search</span>
        </li> -->
        <li>
          <a href="./market.php">
            <i class='bx bx-home-alt'></i>
            <span class="links_name">Home</span>
          </a>
           <span class="tooltip">Home</span>
           <hr>
        </li>
        <li>
         <a href="./market.php?<?php 
         $safe_ordered = @htmlspecialchars($_GET["order"]) ;
         echo isset($_GET["order"]) ? "order={$safe_ordered}&" : "" 
         ?>add=on">
           <i class='bx bx-add-to-queue' style="color: #265DF2;"></i>
           <span class="links_name">New Product</span>
         </a>
         <span class="tooltip">New Product</span>
       </li>
        <li>
          <a href="./market.php?order=all">
            <i class='bx bx-grid-alt'></i>
            <span class="links_name">All Products</span>
          </a>
           <span class="tooltip">All Products</span>
        </li>
        <li>
          <a href="./market.php">
            <i class='bx bx-time' style="color: lightcoral;"></i>
            <span class="links_name">Expired Products</span>
          </a>
           <span class="tooltip">Expired Products</span>
           <hr>
        </li>
        <!-- <li>
         <a href="#">
           <i class='bx bxs-timer' style="color: lightcoral;"></i>
           <span class="links_name">Soon Expired</span>
         </a>
         <span class="tooltip">Soon Expired</span>
       </li> -->

       <!-- <li>
         <a href="#">
           <i class='bx bx-pie-chart-alt-2' ></i>
           <span class="links_name">Products Sold</span>
         </a>
         <span class="tooltip">Products Sold</span>
       </li> -->
       <!-- <li>
         <a href="#">
           <i class='bx bx-cart-alt' ></i>
           <span class="links_name">Stock-Out</span>
         </a>
         <span class="tooltip">Stock-Out</span>
       </li> -->
       <li>
         <a href="./profile.php">
           <i class='bx bx-user' ></i>
           <span class="links_name">Edit Profile</span>
         </a>
         <span class="tooltip">Edit Profile</span>
       </li>
       <li class="profile">
           <div class="profile-details">
             <img src="./images/tornado.png" alt="profileImg">
             <div class="name_job">
               <div class="name"><?php $safe_name = htmlspecialchars($profile["name"]); echo $safe_name;  ?></div>
               <div class="job"><?php $safe_city = htmlspecialchars($profile["city"]); echo $safe_city;  ?> / <?php $safe_district = htmlspecialchars($profile["district"]); echo $safe_district;  ?></div>
             </div>
           </div>
           <a href="index.php?destroy">
              <i class='bx bx-log-out' id="log_out" ></i>
           </a>
       </li>
      </ul>
    </div>









    <section class="home-section">

      <div class="">
        <h1 class="main-header"><?= isset($_GET["order"]) ? "All Products" : "Expired Products" ?></h1>    
          <table cellpadding="0" cellspacing="0" border="0">
            <thead>
              <tr>
                <th>Image</th>
                <th>Title</th>
                <th>Stock</th>
                <th>Price</th>
                <th>Dis. Price</th>
                <th>Exp. Date</th>
                <th>Operations</th>
              </tr>
            </thead>
          </table>
        </div>
        <div class="tbl-content">
          <table cellpadding="0" cellspacing="0" border="0">
            <tbody>
              
            <?php
            if(count($res) == 0) {
              echo "<h1 class='no-item-header'>No Items in Database...</h1>";
            } else {
              foreach($res as $product) {
                $safe_title = htmlspecialchars($product["title"]);
                $safe_stock = htmlspecialchars($product["stock"]);
                $safe_price = htmlspecialchars($product["normal_price"]);
                $safe_discPrice = htmlspecialchars($product["discounted_price"]);
                $safe_date = htmlspecialchars($product["expiration_date"]);

                $ordered =  isset($_GET["order"]) ? "order={$_GET["order"]}&" : "";
                $safe_ordered = htmlspecialchars($ordered);
                //$title = join("-", explode(" ",$product["title"])); // seperating each word then merging with - in order to fit in name attribute on the elements
                echo "
                  <tr>
                    <td>
                      <img src='./images/{$product["image"]}'>
                    </td>
                    <td>{$safe_title}</td>
                    <td>{$safe_stock}</td>
                    <td>{$safe_price}<i style='font-size:14px' class='bx bx-lira'></i></td>
                    <td>{$safe_discPrice}<i style='font-size:14px' class='bx bx-lira'></i></td>
                    <td>{$safe_date}</td>
                    <td>
                      <a href='market.php?{$safe_ordered}id={$product["product_id"]}&edit'><i class='bx bxs-edit show-modal' style='color:cadetblue'></i></a>
                      <a style='text-decoration:none' href='market.php?{$safe_ordered}del={$safe_title}'><i class='bx bx-trash delete-show-modal' style='color:crimson'></i></a>
                    </td>
                  </tr>
                ";
              }
            }
            ?>

            </tbody>
          </table>
        </div>
    </div>
    </section>


    <div class="succ-toast">
      <div class="toast-content">
          <i class="bx bxs-check-circle check"></i>

          <div class="message">
              <span class="text text-1">Success</span>
              <span class="text text-2">Your changes has been saved</span>
          </div>
      </div>
      <i class="close-toast">X</i>

      <div class="progress"></div>
  </div>
    <div class="error-toast">
      <div class="toast-content">
          <i class="bx bxs-check-circle check" style='color:red'></i>

          <div class="message">
              <span class="text text-1">Error</span>
              <?php
              if(!empty($updateErrors)) {
                foreach($updateErrors as $err) {
                  echo "<span class='text text-2'>- {$err}</span> ";
                }
              } else if (!empty($addErrors)) {
                foreach($addErrors as $err) {
                  echo "<span class='text text-2'>- {$err}</span> ";
                }
              }

              ?>
              
          </div>
      </div>
      <!-- <i class="close-toast error-close-toast">X</i> -->

      <div class="progress error-progress"></div>
  </div>


  <div class="add-form-div <?= $operationType == "add" ? "show" : "" ?> <?= count($addErrors) > 0 ? " show" : "  " ?>">
      <div class="add-modal-box">
        <i class="main-icon bx bx-layer-plus"></i>
        <form class="add-form" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="addFormToken" value="<?=$_SESSION["user"]["addFormToken"]?>">
            <div class="step step-1 active">
                <div class="form-group">
                  <div style="text-align: right; margin-bottom:8px">                    
                    <label style="margin-right:5px; color:grey; font-style:italic" for="firstName">Product Title</label>
                  </div>
                    <input class="edit-input" value="<?php $safe_title = isset($_POST["title"])?  htmlspecialchars($_POST["title"]) : ""; echo $safe_title; ?>"  autofocus placeholder="Title of the Product" autocomplete="off" type="text" name="title" />
                </div>
                <button type="button" class="add-form-btn next-btn"><i class="bx bx-arrow-to-right"></i></button>
            </div>
            <div class="step step-2">
                <div class="form-group">
                  <div style="text-align: right; margin-bottom:8px">                    
                    <label style="margin-right:5px; color:grey; font-style:italic" for="firstName">Stock</label>
                  </div>
                  <input class="edit-input" value=<?php $safe_stock = isset($_POST["stock"])?  htmlspecialchars($_POST["stock"]) : ""; echo $safe_stock; ?> placeholder="Stock"  autocomplete="off" type="number" name="stock" />
                </div>
                <button type="button" class="add-form-btn previous-btn"><i class="bx bx-arrow-to-left"></i></button>
                <button type="button" class="add-form-btn next-btn"><i class="bx bx-arrow-to-right"></i></button>
            </div>
            <div class="step step-3">
                <div class="form-group">
                  <div style="text-align: right; margin-bottom:8px">                    
                    <label style="margin-right:5px; color:grey; font-style:italic" for="firstName">Price</label>
                  </div>
                  <input class="edit-input" value=<?php $safe_price = isset($_POST["price"])?  htmlspecialchars($_POST["price"]) : ""; echo $safe_price; ?> placeholder="Price"  autocomplete="off" type="number" name="price" />
                </div>
                <button type="button" class="add-form-btn previous-btn"><i class="bx bx-arrow-to-left"></i></button>
                <button type="button" class="add-form-btn next-btn"><i class="bx bx-arrow-to-right"></i></button>
            </div>
            <div class="step step-4">
                <div class="form-group">
                  <div style="text-align: right; margin-bottom:8px">                    
                    <label style="margin-right:5px; color:grey; font-style:italic" for="firstName">Discounted Price</label>
                  </div>
                  <input class="edit-input" value=<?php $safe_discprice = isset($_POST["discounted_price"])?  htmlspecialchars($_POST["discounted_price"]) : ""; echo $safe_discprice; ?>  placeholder="Discounted Price" autocomplete="off" type="number" name="discounted_price" />
                </div>
                <button type="button"  class="add-form-btn previous-btn"><i class="bx bx-arrow-to-left"></i></button>
                <button type="button" class="add-form-btn next-btn"><i class="bx bx-arrow-to-right"></i></button>
            </div>
            <div class="step step-5">
                <div class="form-group">
                <div style="text-align: right; margin-bottom:8px">                    
                    <label style="margin-right:5px; color:grey; font-style:italic" for="firstName">Expiration Date</label>
                  </div>
                  <input class="edit-input" value=<?php $safe_date = isset($_POST["date"])?  htmlspecialchars($_POST["date"]) : ""; echo $safe_date; ?> placeholder="Expiration Date" autocomplete="off" type="date" name="date" />

                </div>
                <button type="button" class="add-form-btn previous-btn"><i class="bx bx-arrow-to-left"></i></button>
                <button type="button" class="add-form-btn next-btn"><i class="bx bx-arrow-to-right"></i></button>
            </div>
            <div class="step step-6">
                <div class="form-group">
                <div style="text-align: right; margin-bottom:8px">                    
                    <label style="margin-right:5px; color:grey; font-style:italic" for="firstName">Image</label>
                  </div>
                  <label for="images" class="drop-container" id="dropcontainer">
                    <input class="edit-input" placeholder="Image" autocomplete="off" type="file" id="images" name="image">
                  </label>
                </div>
                <button type="button" class="add-form-btn previous-btn"><i class="bx bx-arrow-to-left"></i></button>
                <button type="submit" class="add-form-btn submit-btn"><i class="bx bx-paper-plane"></i></button>
            </div>
        </form>

        <div class="add-modal-buttons">
          <button class="add-modal-button add-close-btn">Close</button>
          <!-- <button class="add-modal-button">Open File</button> -->
        </div>
      </div>
    </div>


  <?php

      if($_SERVER["REQUEST_METHOD"] == "POST" && count($updateErrors) != 0) {
        $safe_oldTitle = htmlspecialchars($_POST['old_title']);
        $safe_image = htmlspecialchars($_POST['image']);
        $safe_title = htmlspecialchars($_POST["title"]);
        $safe_stock = htmlspecialchars($_POST["stock"]);
        $safe_date = htmlspecialchars($_POST["date"]);
        $safe_price = htmlspecialchars($_POST["price"]);
        $safe_discPrice = htmlspecialchars($_POST["discounted_price"]);
        echo "
        <section class='modal active'>
        <div class='modal-box'>
          <i class='bx bx-edit' style='margin-bottom:25px; font-size: 40px;'></i>
          <form class='edit-form' action='' method='POST' enctype='multipart/form-data'>
            <input type='hidden' required name='old_title' value='{$safe_oldTitle}'>
            <input type='hidden' name='addFormToken' value='{$_SESSION["user"]["addFormToken"]}'>
            <input type='hidden' required name='old_image' value='{$safe_image}'>
            <input class='edit-input' required type='text' value='{$safe_title}' name='title' placeholder='Title...'>
            <input class='edit-input' required type='number' value='{$safe_stock}' name='stock' placeholder='Stock'>
            <label for=''>Expiration Date:</label>
            <input class='edit-input' required type='date' value='{$safe_date}' name='date' placeholder='Expiration Date...'>
            <input class='edit-input' required type='number' value='{$safe_price}' name='price' placeholder='Normal Price'>
            <input class='edit-input' required type='number' value='{$safe_discPrice}' name='discounted_price' placeholder='Discounted Price'>
            <label for='images' class='drop-container' id='dropcontainer'>
              <input class='edit-input' type='file' id='images' name='image'>
            </label>
            <div class='buttons' style='text-align: right;'>
              <button class='toast-button' name='update_btn' value='on'>Update</button>
              <button class='close-btn' >Close</button>
            </div>";
            foreach($updateErrors as $err) {
              echo "<div class='error-msg'>{$err}</div>";
            }
            echo "
          </form>
        </div>
      </section>
        ";
      } else if ( $operationType == "edit") {
        $stmt = $db->prepare("select * from products where product_id = ?");
        $stmt->execute([$_GET["id"]]);
        $theProduct = $stmt->fetch();

        var_dump($theProduct);


        $safe_title = htmlspecialchars($theProduct['title'],ENT_QUOTES);
        var_dump($safe_title);
        $safe_image = htmlspecialchars($theProduct['image']);
        $safe_stock = htmlspecialchars($theProduct["stock"]);
        $safe_date = htmlspecialchars($theProduct["expiration_date"]);
        $safe_price = htmlspecialchars($theProduct["normal_price"]);
        $safe_discPrice = htmlspecialchars($theProduct["discounted_price"]);

        echo "
        <section class='modal active'>
        <div class='modal-box'>
          <i class='bx bx-edit' style='margin-bottom:25px; font-size: 40px;'></i>
          <form class='edit-form' action='' method='POST' enctype='multipart/form-data'>
            <input type='hidden' required name='old_title' value='{$safe_title}'>
            <input type='hidden' name='addFormToken' value='{$_SESSION["user"]["addFormToken"]}'>
            <input type='hidden' required name='old_image' value='{$safe_image}'>
            <input class='edit-input' required type='text' value='{$safe_title}' name='title' placeholder='Title...'>
            <input class='edit-input' required type='number' value='{$safe_stock}' name='stock' placeholder='Stock'>
            <label for=''>Expiration Date:</label>
            <input class='edit-input' required type='date' value='{$safe_date}' name='date' placeholder='Expiration Date...'>
            <input class='edit-input' required type='number' value='{$safe_price}' name='price' placeholder='Normal Price'>
            <input class='edit-input' required type='number' value='{$safe_discPrice}' name='discounted_price' placeholder='Discounted Price'>
            <label for='images' class='drop-container' id='dropcontainer'>
              <input class='edit-input' type='file' id='images' name='image'>
            </label>            
            <div class='buttons' style='text-align: right;'>
              <button class='toast-button' name='update_btn' value='on'>Update</button>
              <button class='close-btn' >Close</button>
            </div>
          </form>
        </div>
      </section>
        ";
      }
    ?>
    
    

    <!-- <section class='modal <?= !empty($currTitle) && $operationType == "delete" ? "active" : "" ?>'>
        <div class='modal-box' style='min-height:220px; max-width:300px'>
          <i class='bx bx-trash' style='margin-bottom:25px; font-size: 40px; color:crimson'></i>
            <div class='buttons' style='text-align: right;'>
              <button class='toast-button delete' style='background-color:crimson'><a style='color:white; text-decoration:none' href='market.php?del=<?= $currTitle ?>&approved=on'>Delete</a></button>
              <button class='close-btn'>Close</button>
            </div>
        </div>
      </section> -->

      <section class='delete-modal <?= !empty($currTitle) && $operationType == "delete" ? "show" : "" ?>'>
        <div class='delete-modal-box' style='min-height:220px; max-width:300px'>
          <i class='bx bx-trash' style='margin-bottom:25px; font-size: 40px; color:crimson'></i>
            <div class='buttons' style='text-align: right;'>
              <button class='delete-toast-button delete' style='background-color:crimson'><a style='color:white; text-decoration:none' href='market.php?del=<?= $currTitle ?>&approved=on'>Delete</a></button>
              <button class='delete-close-btn'>Close</button>
            </div>
        </div>
      </section>



    <script>
  $(document).ready(function() {
    // Sidebar Toggle
    let sidebar = $(".sidebar");
    let sidebarCloseBtn = $("#btn");
    let searchBtn = $(".bx-search");



    sidebarCloseBtn.click(function() {
      sidebar.toggleClass("open");
      menuBtnChange(); // Calling the function (optional)
    });

    searchBtn.click(function() {
      sidebar.toggleClass("open");
      menuBtnChange(); // Calling the function (optional)
    });

    // Optional function to change sidebar button icon
    function menuBtnChange() {
      if (sidebar.hasClass("open")) {
        sidebarCloseBtn.removeClass("bx-menu").addClass("bx-menu-alt-right");
      } else {
        sidebarCloseBtn.removeClass("bx-menu-alt-right").addClass("bx-menu");
      }
    }

    // Modal Popup
    const section = $(".modal");
    let showBtns = $(".show-modal");
    let modelCloseBtn = $(".close-btn");

    showBtns.each(function() { // Looping through all show buttons
      $(this).click(function() {
        section.addClass("active");      
      });
    });
    
    modelCloseBtn.click(function(e) { // close button for modal box
      e.preventDefault();
      section.removeClass("active");
      let ov = $(".ov");
      ov.removeClass("active");

    });

    const delSection = $(".delete-modal");
    let delShowBtns = $(".delete-show-modal");
    let delModelCloseBtn = $(".delete-close-btn");

    delShowBtns.each(function() { // Looping through all show buttons
      $(this).click(function() {
        delSection.addClass("show");      
      });
    });
    
    delModelCloseBtn.click(function(e) { // close button for modal box
      delSection.removeClass("show");
      let ov = $(".ov");
      ov.removeClass("active");

    });

 








    // Toast Notification
    const toast_button = $(".toast-button");
    const toast = $(".succ-toast");
    const progress = $(".progress");
    const closeToastIcon = $(".close-toast");

    const errorToast = $(".error-toast");
    const errorProgress = $(".error-progress");
    const errorCloseToastIcon = $(".error-close-toast");




    let timer1, timer2;
    /*toast_button.click(function() {});*/
    <?php 
      if(($_SERVER["REQUEST_METHOD"] == "POST" && (count($updateErrors) != 0)) || (($_SERVER["REQUEST_METHOD"] == "POST" && count($addErrors) != 0))) {
        echo "
          errorToast.addClass('active'); // to show toast notifications
          errorProgress.addClass('active'); // to show progress bar
    
          timer1 = setTimeout(function() {
            errorToast.removeClass('active');
          }, 3000);
    
          timer2 = setTimeout(function() {
            errorProgress.removeClass('active');
          }, 3300);
        ";
      }
      else if(($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_btn"])) || $operationType == "add-completed" || isset($_GET["deleted"])) {
          echo "
          section.removeClass('active'); // Closing the model
          toast.addClass('active'); // to show toast notifications
          progress.addClass('active'); // to show progress bar
          let ov = $('.ov');
          ov.removeClass('active');
    
          timer1 = setTimeout(function() {
            toast.removeClass('active');
          }, 3000);
    
          timer2 = setTimeout(function() {
            progress.removeClass('active');
          }, 3300);
          ";
      }
    ?>

    closeToastIcon.click(function() {
      toast.removeClass("active");
      let ov = $(".ov");
      ov.removeClass("active");


      clearTimeout(timer1);
      clearTimeout(timer2);

      setTimeout(function() {
        progress.removeClass("active");
      }, 300);
    });

    });









    // ADD FORM
    
  // Cache Selectors
  var addFormDiv = $('.add-form-div');
  var addFormCloseBtn = $('.add-close-btn');


  addFormCloseBtn.click(function() {
    addFormDiv.removeClass('show');
    let ov = $(".ov");
    ov.removeClass("active");
    console.log("ada");
  });

  // Multi-Step Form Handling
  var steps = $('.add-form .step');
  var nextBtn = $('.add-form .next-btn');
  var prevBtn = $('.add-form .previous-btn');
  var addForm = $('.add-form');

  // Next Button Click Event Handler
  nextBtn.click(function() {
    changeStep('next');
  });

  // Previous Button Click Event Handler
  prevBtn.click(function() {
    changeStep('prev');
  });

  // Form Submission Handling
  /*addForm.submit(function(e) {
    e.preventDefault();

    var inputs = [];
    addForm.find('input').each(function() {
      inputs.push({
        name: $(this).attr('name'),
        value: $(this).val()
      });
    });

    console.log(inputs);
    addForm.trigger('reset');
  });*/

  // Change Step Function
  function changeStep(btn) {
    var active = steps.filter('.active');
    var index = steps.index(active);
    
    steps.removeClass('active');
    
    if (btn === "next") {
      index++;
    } else if (btn === "prev") {
      index--;
    }
    
    if (index >= 0 && index < steps.length) {
      steps.eq(index).addClass('active');
    }
  }


    </script>


  </body>
</html>
