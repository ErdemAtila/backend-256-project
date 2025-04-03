<?php

require "db.php" ;
$user = $_SESSION["user"];
// var_dump($user);
$products = $_SESSION["products"];

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Shopnado</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" href="style2.css" />
    <link flex href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <!-- <script src="script.js" defer></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  </head>
  <body>

    <div class="sidebar">
      <div class="logo-details">
          <img class="icon" src="./images/tornado.png" alt="">
          <div class="logo_name">Shopnado</div>
          <i class='bx bx-menu' id="btn" ></i>
      </div>
      <ul class="nav-list">
        <li>
          <form action="./ydk.php?status=search" method="post">
            <i class='bx bx-search' ></i>
            <input type="text" placeholder="Search..." name="searchBox">
           <span class="tooltip">Search</span>
          </form>
        </li>
        <li>
          <a href="./ydk.php?status=home">
            <i class='bx bx-home-alt'></i>
            <span class="links_name">Home</span>
          </a>
           <span class="tooltip">Home</span>
           <hr>
        </li>
        <li>
          <a href="./ydk.php?status=allProducts">
            <i class='bx bx-grid-alt'></i>
            <span class="links_name">All Products</span>
          </a>
           <span class="tooltip">All Products</span>
        </li>
       
        <li>
         <a href="./ydk.php?status=soonExpired">
           <i class='bx bxs-timer' style="color: lightcoral;"></i>
           <span class="links_name">Soon Expired</span>
         </a>
         <span class="tooltip">Soon Expired</span>
       </li>
       <li>
         <a href="./ydk.php?status=stockOut">
           <i class='bx bx-cart-alt' ></i>
           <span class="links_name">Stock-Out</span>
         </a>
         <span class="tooltip">Stock-Out</span>
       </li>
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
               <div class="name"><?php $safe_name = htmlspecialchars($user["name"]); echo $safe_name;  ?></div>
               <div class="job"><?php $safe_city = htmlspecialchars($user["city"]); echo $safe_city;  ?> / <?php $safe_district = htmlspecialchars($user["district"]); echo $safe_district;  ?></div>
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
       
        <h1 class="main-header" ><p style="text-align:left; padding:15px">
        <?php
            echo "Welcome <span style='font-style:italic'>{$user['name']}</span> . . .";
        ?>
      </p><a href="userMain.php"><i style="font-size: 45px;" class="bx bx-cart"></i></a></h1>    

          <table cellpadding="0" cellspacing="0" border="0">
            <thead>
              <tr>
                <th></th>
                <th>Title</th>
                <th>Market</th>
                <th>City</th>
                <th>District</th>
                <th>Expiration Date</th>
                <th>Price in Cart</th>
                <th>In Your Cart</th>
                <th></th>
              </tr>
            </thead>
          </table>
        </div>

        <div class="tbl-content">
          <table cellpadding="0" cellspacing="0" border="0">
            <tbody>
                <?php
                    if(empty($products))
                       {
                        echo "<div>";
                            echo "<img style='  display: block;
                            margin-left: auto;
                            margin-right: auto;
                            width: 80%;
                            position:relative;' 
                            src='./images/nothingOnCart.jpg' alt=''>";
                            echo "<div style='position: absolute;
                            top:230px;
                            left: 150px;
                            font-size:30px;
                            font-style:italic'>
                            It seems like you didn't start shopping...</div>";
                            echo "<div style='position: absolute;
                            top: 280px;
                            left: 150px;
                            font-size:40px'>
                            <a href='./ydk.php?status=home'>Start with sales on products</a></div>";
                        echo "</div>";
                       }
                    else
                        {
                          // var_dump($_SESSION["ids"]);
                        showProductsForUser($products);
                       
                        }

                       

                ?>
                
              

            </tbody>
          </table>
          <?php callPurchaseButton(); ?>

        </div>
    </div>
    
    </section>


    <div class="toast">
      <div class="toast-content">
          <i class="bx bxs-check-circle check"></i>
          <div class="message">
                <?php
                if(empty($cartErrors)) {
                  echo "
                  <span class='text text-1'>Success</span>
                  <span class='text text-2'>Your changes has been saved</span>
                  ";
                } else {
                  echo "
                  <span class='text text-1'>Errorr!!!</span>
                  <span class='text text-2'>Check Stock!!</span>
                  ";
                }
                ?>

          </div>
      </div>
      <i class="close-toast">X</i>

      <div class="progress"></div>
  </div>

  <div class="add-form-div">
        <div class="add-modal-box">
            <i class="main-icon bx bx-layer-plus"></i>
            <input class="edit-input" placeholder="Price" autocomplete="off" type="number" name="price" />
            <input class="edit-input" placeholder="Price" autocomplete="off" type="number" name="price" />
            <input class="edit-input" placeholder="Price" autocomplete="off" type="number" name="price" />

            <div class="add-modal-buttons">
                <button class="add-modal-button">Other</button>
                <button class="add-modal-button add-close-btn">Close</button>
            </div>
        </div>
    </div>



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




    const toast = $(".toast");
        const progress = $(".progress");
        const closeToastIcon = $(".close-toast");
        
        closeToastIcon.click(function() {
          toast.removeClass('active'); // to show toast notifications
                    progress.removeClass('active'); // to show progress bar
              
        });

        <?php
        if(isset($_POST['update'])){ 
                    echo "toast.addClass('active'); // to show toast notifications
                    progress.addClass('active'); // to show progress bar
              
                    timer1 = setTimeout(function() {
                      toast.removeClass('active');
                    }, 3000);
              
                    timer2 = setTimeout(function() {
                      progress.removeClass('active');
                    }, 3300);";
                }
        ?>


    // ADD FORM
    
  // Cache Selectors
  var addFormDiv = $('.add-form-div');
  var addFormCloseBtn = $('.add-close-btn');
  var showAddFormBtn = $(".main-header");

 /* showAddFormBtn.click(function() { 
        addFormDiv.addClass("show");      
    });


    addFormCloseBtn.click(function() {
        addFormDiv.removeClass('show');
    });*/


  }) // end of the "document" ready 
    </script> 


  </body>
</html>
