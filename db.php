<?php

    const DSN = "mysql:host=localhost;dbname=test;charset=utf8mb4" ;
    const USER = "std" ;
    const PASSWORD = "" ;
    const NUMOFPRODUCT= 5;
    const PERPAGE= 4;
    $cartErrors = array();
    try {
    $db = new PDO(DSN, USER, PASSWORD) ; 
    } catch(PDOException $e) {
        http_response_code(404);
        echo "Set username and password in 'db.php' appropriately" ;
        exit ;
    }


#Beginning Part of the MARKET
    function uploadImage($imageBoxName) {
        global $db;
        $file = "";

        if ( !empty($_FILES[$imageBoxName]["tmp_name"])) {
            $folder = "images";

            extract($_FILES[$imageBoxName]) ; // $name, $tmp_name, $size, $error ...
            $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION)) ;
            $imgExt = ["png", "jpg", "jpeg"] ; // white list
            if ( !in_array($ext, $imgExt)) {

              return "Not an image file";
              //throw new Exception("Not an image file") ; 
            } else if ( $size > 1024*1024 ) {

              return "Too big image file";
              //throw new Exception("Too big image file") ;
            } else {
                $file = sha1($tmp_name . $name . $size . uniqid()) . ".$ext";

                move_uploaded_file($tmp_name, $folder . "/" . $file) ;

            }

          }

          return $file;
          }


          function checkUser($email, $pass,&$user) {
            global $db ;
          
            $stmt = $db->prepare("select * from users where email=?") ;
            $stmt->execute([$email]) ;
            $user = $stmt->fetch() ;
            if ( $user) {
                return password_verify($pass, $user["password"]) ;
            }
            return false ;
          }
          function setTokenByEmail($email, $token) {
            global $db ;
            $stmt = $db->prepare("update users set remember = ? where email = ?") ;
            $stmt->execute([$token, $email]) ;
          }
          function getUserByToken($token) {
            global $db ;
            $stmt = $db->prepare("select * from users where remember = ?") ;
            $stmt->execute([$token]) ;
            return $stmt->fetch() ;
          }

          #Beginning Part of the CONSUMER
                 
session_start();
  //CSRF attacks

function findNonAvailable(){

    global $db;
    $stmt = "Select * from products where stock = 0 OR expiration_date < CURRENT_DATE ";
    $nonAvailable = $db->query($stmt)->fetchAll();
    return $nonAvailable;
}

# This part is for the update the dataBase
function updateDB($proId,$howManyPurchased){

    $object = findTheProduct($proId);
    $oldStock = $object["stock"];
    global $db;
    $stmt = "UPDATE products
    SET stock = $oldStock - $howManyPurchased
    WHERE product_id = $proId";
    $stockUpdatedProduct = $db->query($stmt)->fetch();
    // var_dump($stockUpdatedProduct);
}

if(isset($_GET["purchase"])){

    //var_dump($_SESSION["products"]);
    foreach($_SESSION['products'] as $k=>$v){
        
        $purchased = $_SESSION['products'][$k]["adet"];
        updateDB($v["product_id"],$purchased);
        unset($_SESSION["products"][$k]); // I have to take these off...
        unset($_SESSION["ids"][$k]);
    }
    //var_dump($_SESSION["products"]);

}


if(!isset($_SESSION["products"]))
    {
        
        $_SESSION["products"]  = [];
        $_SESSION["ids"] = [];
        
    }
else{

}
// var_dump(isset($_POST));
// var_dump(isset($_POST["size"]));

if(isset($_GET["product"]))
    {         
      
        
        $howMany = $_POST["size"];
        // var_dump($howMany);
        if($howMany<=0){
            $cartErrors[] = "Quantity must be greater than 0!!!!!" ;
            
        }
        else{ 
            $id = $_GET["product"];
            // var_dump($id);
            $theProduct = findTheProduct($id);
            // var_dump($theProduct);
            
            if(empty($_SESSION["ids"]))
            {       
                
                $theProduct["adet"]=$howMany;
                if( $theProduct["adet"] >$theProduct["stock"]){
                    $cartErrors[] = "Check the Stocks!!!!!" ;


                }
                else{
                array_push($_SESSION["products"], $theProduct);
                array_push($_SESSION["ids"],$id);
                }
            }
            else if(in_array($id, $_SESSION["ids"]))
            {
            
                foreach($_SESSION["products"] as $k=>$p){
                    // var_dump($p);
                    if($p["product_id"]==$id)
                    {
                            if( $howMany+$_SESSION["products"][$k]["adet"] >$theProduct["stock"]){
                                $cartErrors[] = "Check the Stocks!!!!!" ;

                            }else{
                            $_SESSION["products"][$k]["adet"]=$p["adet"]+$howMany;
                            }
                            break;
                        }
                }
            }
            else
                {
                    
                    $theProduct["adet"]=$howMany;
                    if( $howMany>$theProduct["stock"]){
                    
                        $cartErrors[] = "Check the Stocks!!!!!" ;

                    }else{
                    array_push($_SESSION["products"], $theProduct);
                    array_push($_SESSION["ids"],$id);
                    }
                }
            }
        }
        //  var_dump($theProduct);
        // var_dump( $_SESSION["ids"]);



    #this is for update part
if(isset($_POST["update"])){

    $updateSize = $_POST["newSize"];

    foreach($_SESSION["products"] as $k=>$p){
        // var_dump($p);
        if($p["product_id"]==$_GET["updatedProduct"])
           {
                if( $updateSize> $_SESSION["products"][$k]["stock"]){
                   $cartErrors[]="Stock has been" ;

                }else{
                    $_SESSION["products"][$k]["adet"]=  $updateSize;
                }
                break;
            }
    }


}

if(isset($_GET["delete"]))
{

    $deleteId = $_GET["delete"];

    foreach($_SESSION["products"] as $k=>$p){
        // var_dump($p);
        if($p["product_id"]==$deleteId)
           {
                // var_dump("buldum");
                unset($_SESSION["products"][$k]);
                break;
            }
    }

    foreach($_SESSION["ids"] as $k=>$p){
        
        if($p == $deleteId)
           {
                // var_dump("buldum");
                unset($_SESSION["ids"][$k]);
                break;
            }
    }

    // var_dump( $_SESSION["ids"]);
    
}

function findTheProduct($id){
    global $db;
    $stmt = $db->prepare("Select * from products where product_id= ? and expiration_date > CURRENT_DATE");
    $stmt->execute([$id]);
    $pro = $stmt->fetch();
    return $pro;
}
function findTheMarket($marketId){
    global $db;
    $stmt = $db->prepare("Select * from users where type= 'market' AND id = ?");
    $stmt->execute([$marketId]);
    $market = $stmt->fetch();
    return $market;
}
function soonExpiredProducts(){

    global $db;

    $stmt = "Select * from products where";
    $products = $db->query($stmt)->fetchAll(PDO::FETCH_ASSOC);
    return $products;
}

function getAllProducts(){

    global $db;
    $stmt = "Select * from products where stock <> 0 and expiration_date > CURRENT_DATE";
    $products = $db->query($stmt)->fetchAll(PDO::FETCH_ASSOC);
    return $products;
}

function productsOnSale(){


    $products = getAllProducts();
    foreach($products as $product){
        $dynamicAssocArray[$product["product_id"]] = round(($product["normal_price"]-$product["discounted_price"])/$product["normal_price"]*100 , 2);

    }
   
   arsort($dynamicAssocArray);
   
   return $dynamicAssocArray;
}

/*
     function productsOnSale(){

    $products = getAllProducts();
    foreach($products as $product){
        $dynamicAssocArray[$product["product_id"]] = round(($product["normal_price"]-$product["discounted_price"])/$product["normal_price"]*100 , 2);

    }
   arsort($dynamicAssocArray);
   return $dynamicAssocArray;
} */
function showProductsForUser($printedArray){
    $total = 0 ;
    $products = $printedArray;
    // var_dump($products);
    foreach($products as $k=>$v){
        $total += $products[$k]['adet'] * $products[$k]['discounted_price'] ;
        $market = findTheMarket($products[$k]["market_id"]);
        echo "<tr>";
            echo "<td><img src='./images/{$products[$k]['image']}'></td>";
            echo "<td>{$products[$k]['title']}</td>";
            echo "<td>{$market['name']}</td>";
            echo "<td>{$market['city']}</td>";
            echo "<td>{$market['district']}</td>";
            echo "<td>{$products[$k]['expiration_date']}</i></td>";
            echo "<td>{$products[$k]['discounted_price']}<i style='font-size:14px' class='bx bx-lira'></i></td>";
            echo "<form action='?updatedProduct={$products[$k]['product_id']}' method=post>";
            echo "<input type='hidden' name='consumerToken' value='{$_SESSION['user']['consumerToken']}'>";
            echo "<td style='display:flex;justify-content: flex-end;align-items: center; gap: 30px'>";
            echo "<div style='display:flex; justify-content: center;align-items: center; '>";
            echo "<input style='width:60px; text-align:center; height:30px;' class='quantity' type='number' value='{$products[$k]['adet']}' name='newSize'>";
            echo "</div>";
            echo "</td>";
            echo "<td>";
            echo "<div style='display:flex; justify-content: center;align-items: center; gap:10px'>";
            echo "<input type='submit' value='💾' name='update' style='height:35px; width:35px'></input>";
            echo "<a href='?delete={$products[$k]['product_id']}'><i class='bx bx-trash' style='color:cadetblue'></i></a>";
            echo "</div>";
            echo "</td>";
            echo "</form>";
        echo "</tr>";
    }

    //Let's do the Total Part
    echo "<tr>";
    echo "<td colspan='7'</td>";
    echo "<td>Total : </td>";
    echo "<td style='text-align:center'> $total <i style='font-size:14px' class='bx bx-lira'></i></td>";
    echo "</tr>";


   
}

function showAllProducts(){

    $products = getAllProducts();
    foreach($products as $k=>$v){
        $market = findTheMarket($products[$k]["market_id"]);
        echo "<tr>";
            echo "<td><img src='./images/{$products[$k]['image']}'></td>";
            echo "<td>{$products[$k]['title']}</td>";
            echo "<td>{$market['name']}</td>";
            echo "<td>{$market['city']}</td>";
            echo "<td>{$market['district']}</td>";
            echo "<td>{$products[$k]['stock']}</td>";
            echo "<td>{$products[$k]['expiration_date']}</i></td>";
            echo "<td>{$products[$k]['normal_price']}<i style='font-size:14px' class='bx bx-lira'></i></td>";
            echo "<td>{$products[$k]['discounted_price']}<i style='font-size:14px' class='bx bx-lira'></i></td>";
            echo "<form action='./ydk.php?product={$products[$k]['product_id']}&status={$_GET['status']}' method=post>";
            echo "<input type='hidden' name='consumerToken' value='{$_SESSION['user']['consumerToken']}'>";
            echo "<td style='display:flex;justify-content: flex-end;align-items: center; gap: 30px'>";
            echo "<div style='display:flex; justify-content: center;align-items: center; gap:10px'>";
            echo "<input style='width:30px; text-align:center; height:30px;' class='quantity' type='number' value='1' name='size'>";
            echo "</div>";
            echo "<input type='submit' value='🛒' name='addCartButton' style='height:35px; width:35px'></input>";
            echo "</td>";
            echo "</form>";
        echo "</tr>";
    }
}


function showProductsonSale($status){

    
    $productsOnSale = productsOnSale();
    // VAR_DUMP($productsOnSale);
    $counter = 1;
    foreach($productsOnSale as $k=>$v){
        
        $saleProduct = findTheProduct($k);
        // var_dump($saleProduct);
        // var_dump($saleProduct);
        if($counter>NUMOFPRODUCT)
            break;
        $market = findTheMarket($saleProduct["market_id"]);
        echo "<tr>";
        echo "<td><img src='./images/{$saleProduct['image']}'></td>";
        echo "<td>{$saleProduct['title']}</td>";
        echo "<td>{$market['name']}</td>";
        echo "<td>{$market['city']}</td>";
        echo "<td>{$market['district']}</td>";
        echo "<td>{$saleProduct['stock']}</td>";
        echo "<td>{$saleProduct['expiration_date']}</i></td>";
        echo "<td>{$saleProduct['normal_price']}<i style='font-size:14px' class='bx bx-lira'></i></td>";
        echo "<td>{$saleProduct['discounted_price']}<i style='font-size:14px' class='bx bx-lira'></i></td>";
        echo "<td>{$productsOnSale[$k]}%</td>";
        
        echo "<form action='?product={$saleProduct['product_id']}' method=post>";
        echo "<input type='hidden' name='consumerToken' value='{$_SESSION['user']['consumerToken']}'>";
        echo "<td style='display:flex;justify-content: flex-end;align-items: center; gap: 30px'>";
        echo "<div style='display:flex; justify-content: center;align-items: center; gap:10px'>";
        echo "<input style='width:30px; text-align:center; height:30px;' class='quantity' type='number' value='1' name='size'>";
        echo "</div>";
        echo "<input type='submit' value='🛒' name='addCartButton' style='height:35px; width:35px'></input>";
        echo "</td>";
        echo "</form>";
        echo "</tr>";
        #to show only NUMOFPRODUCT number
        $counter++;
    }
}

# let's write a function for search part

function searchedAllProducts($searched, $userCity, $userDistrict){

    htmlspecialchars($searched);
    global $db;
    $stmt = $db->prepare("select * from products p INNER JOIN ( select id, district, name FROM users WHERE type = 'market' AND city = ?) u ON p.market_id = u.id WHERE p.title LIKE
    '%$searched%' and expiration_date > CURRENT_DATE ORDER BY case when u.district = ? then 1 else 5 end;");
    $stmt->execute([$userCity,$userDistrict]);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // var_dump($products);
    return $products;
}

function searchedLimitedProducts($searched, $userCity, $userDistrict, $initialProduct){

    $limitation = PERPAGE;
    global $db;
    $stmt = "select * from products p INNER JOIN ( select id, district, name FROM users WHERE type = 'market' AND city = '$userCity') u ON p.market_id = u.id WHERE p.title LIKE
    '%$searched%' and expiration_date > CURRENT_DATE ORDER BY case when u.district = '$userDistrict' then 1 else 5 end limit $initialProduct, $limitation ;";
    $products = $db->query($stmt)->fetchAll(PDO::FETCH_ASSOC);
    return $products;
}

function showSearched($searched, $city, $district){

    // var_dump($_SESSION);
    $profile = $_SESSION["user"];
    $productsAll = searchedAllProducts($searched, $city, $district);
    if(empty($productsAll)){
        echo "<tr>";
        echo "<td style='text-align:center;' colspan= 10>We couldn't find any products for <span style='font-style:italic; '>'$searched' </span> in stocks of <span style='font-style:italic; '> '{$profile['city']}'</span></td>";        echo "</tr>";
    }
    $totalPage = ceil(count($productsAll)/PERPAGE);
    $initialProduct = isset($_GET["currentPage"]) ? (  intval($_GET["currentPage"]) - 1) * PERPAGE : 0;


    $products = searchedLimitedProducts($searched, $city, $district,$initialProduct);


    foreach($products as $k=>$v){


        $market = findTheMarket($products[$k]["market_id"]);
        echo "<tr>";
        echo "<td><img src='./images/{$products[$k]['image']}'></td>";
        echo "<td>{$products[$k]['title']}</td>";
        echo "<td>{$market['name']}</td>";
        echo "<td>{$market['city']}</td>";
        echo "<td>{$market['district']}</td>";
        echo "<td>{$products[$k]['stock']}</td>";
        echo "<td>{$products[$k]['expiration_date']}</i></td>";
        echo "<td>{$products[$k]['normal_price']}<i style='font-size:14px' class='bx bx-lira'></i></td>";
        echo "<td>{$products[$k]['discounted_price']}<i style='font-size:14px' class='bx bx-lira'></i></td>";
        
        echo "<form action='?product={$products[$k]['product_id']}&status={$_GET['status']}&search=$searched' method=post>";
        echo "<input type='hidden' name='consumerToken' value='{$_SESSION['user']['consumerToken']}'>";
        echo "<td style='display:flex;justify-content: flex-end;align-items: center; gap: 30px'>";
        echo "<div style='display:flex; justify-content: center;align-items: center; gap:10px'>";
        echo "<input style='width:30px; text-align:center; height:30px;' class='quantity' type='number' value='1' name='size'>";
        echo "</div>";
        echo "<input type='submit' value='🛒' name='addCartButton' style='height:35px; width:35px'></input>";
        echo "</td>";
        echo "</form>";
        echo "</tr>";


    }

      return $totalPage;

}

function showSoonExpired(){

    #current time object
    $today = new DateTime() ; // current date and time
    // var_dump($today);
    $products = getAllProducts();
    foreach($products as $product){
        // var_dump($product["expiration_date"]);
        $expiration= new DateTime($product["expiration_date"]);
        // var_dump($expiration);
        $interval = $today->diff($expiration) ;

        if($interval->days <= 40){

            // var_dump($interval);
            // var_dump($interval->m);
            // var_dump($expiration);
            $market = findTheMarket($product["market_id"]);
            echo "<tr>";
                echo "<td><img src='./images/{$product['image']}'></td>";
                echo "<td>{$product['title']}</td>";
                echo "<td>{$market['name']}</td>";
                echo "<td>{$market['city']}</td>";
                echo "<td>{$market['district']}</td>";
                echo "<td>{$product['stock']}</td>";
                echo "<td>{$product['expiration_date']}</i></td>";
                echo "<td>{$product['normal_price']}<i style='font-size:14px' class='bx bx-lira'></i></td>";
                echo "<td>{$product['discounted_price']}<i style='font-size:14px' class='bx bx-lira'></i></td>";

                echo "<form action='?product={$product['product_id']}&status={$_GET['status']}' method=post>";
                echo "<input type='hidden' name='consumerToken' value='{$_SESSION['user']['consumerToken']}'>";
                echo "<td style='display:flex;justify-content: flex-end;align-items: center; gap: 30px'>";
                echo "<div style='display:flex; justify-content: center;align-items: center; gap:10px'>";
                echo "<input style='width:30px; text-align:center; height:30px;' class='quantity' type='number' value='1' name='size'>";
                echo "</div>";
                echo "<input type='submit' value='🛒' name='addCartButton' style='height:35px; width:35px'></input>";
                echo "</td>";
                echo "</form>";
            echo "</tr>";
        }
    }

}

function callPurchaseButton(){

     //Let's do the purchase button
     echo " <div style='font-size:1.5em;text-align:right; margin-top:20px'>";
     echo "<div style='font-size:1.5em'>"   ; 
     echo " <a style='color:white;' href='?purchase=' class='button5' >Purchase</a>";
     echo "</div>";
}

function showStockOut(){

    #this part is showing the size of products which is not available in the stock
    $products = findNonAvailable();
    if(empty($products)){
        echo "<tr colspan=2>";
        echo "<td style='text-align:center' colspan = 9>It seems like all products are available on the market... Enjoy your shopping!</td>";
        echo "</tr>";
        echo "<div>";
        echo "<img style='  display: block;
        margin-left: auto;
        margin-right: auto;
        width: 40%;
        position:relative;' 
        src='./images/farm.jpg' alt=''>";
        echo "</div>";
    }
    else{

        foreach($products as $k=>$v){

            $market = findTheMarket($products[$k]["market_id"]);
            echo "<tr>";
            echo "<td><img src='./images/{$products[$k]['image']}'></td>";
            echo "<td>{$products[$k]['title']}</td>";
            echo "<td>{$market['name']}</td>";
            echo "<td>{$market['city']}</td>";
            echo "<td>{$market['district']}</td>";
            echo "<td>{$products[$k]['stock']}</td>";
            echo "<td>{$products[$k]['expiration_date']}</i></td>";
            echo "<td>{$products[$k]['normal_price']}<i style='font-size:14px' class='bx bx-lira'></i></td>";
            echo "<td>{$products[$k]['discounted_price']}<i style='font-size:14px' class='bx bx-lira'></i></td>";
            echo "<td><img src='./images/work.png' alt='' style='width:95%'></td>";
            echo "</tr>";
        }
    }
}
?>