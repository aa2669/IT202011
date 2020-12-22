<?php
session_start();
$db = mysqli_connect('localhost', 'aa2669','nkP1JzTbcM9FN1eu','aa2669');

require_once('adminheader.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="css/styles.css">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<title>products</title>
</head>
<body>

<?php

if ($_SESSION["email"] == 'admin@gmail.com') {
    $purchase_history = "SELECT DISTINCT oit.quantity as oit_quantity, oit.unitprice as price,
     oit.product_id as product_id, oit.id as oit_id, p.name as name, p.category as category
    FROM orderitems as oit
    INNER JOIN orders as o ON oit.order_id = o.order_id
    INNER JOIN products as p ON oit.product_id = p.product_id";
  } else {
    $purchase_history = "SELECT DISTINCT oit.quantity as oit_quantity, oit.unitprice as price,
     oit.product_id as product_id, oit.id as oit_id, p.name as name, p.category as category
    FROM orderitems as oit
    INNER JOIN orders as o ON oit.order_id = o.order_id
    INNER JOIN products as p ON oit.product_id = p.product_id WHERE o.user_id = {$_SESSION['user_id']}";
  }

  $purchase_history_query = mysqli_query($db, $purchase_history);
  if (!$purchase_history_query) {
      die(mysqli_error($db));
    die("<h5 style='text-align: center;'>Something went wrong</h5>");
  }
 
  if (mysqli_num_rows($purchase_history_query) < 1) {
    die("<h5 style='text-align: center;'>No items purchased yet</h5>");
  }


 
  while ($row = mysqli_fetch_assoc($purchase_history_query)) {
    $name = $row["name"];
    $quantity = $row["oit_quantity"];
    $price = $row["price"];
    $total_price = $price * $quantity;
    $category = $row["category"];
    $product_id = $row["product_id"];

    ?>
    
     <div class="w3-cell-row"  style="text-align:center;margin:15px;">

     <div class="w3-card-4 w3-cell" style="width:40%;margin-right:20px;margin-top:20px;margin-bottom:20px;align-content:center;">
         
         <header class="w3-container w3-blue">
         <h1>  <?php echo $name ?></h1>
         </header>

         <div class="w3-container">
         <h5> Price: $<?php echo $total_price?>
         
         </div>

         <footer class="w3-container w3-blue">
         <p> Quantity: <?php echo $quantity ?></p>
         </footer>
         
     </div>
     </div>

 <?php
  }


        $mess="";

        if (isset($_GET['pageno'])) {
            $pageno = $_GET['pageno'];
        } else {
            $pageno = 1;
        }
        $no_of_records_per_page = 10;
        $offset = ($pageno-1) * $no_of_records_per_page;


        

?>

</body>
</html>