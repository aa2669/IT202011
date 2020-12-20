<?php
session_start();
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
require_once('adminheader.php');
?>

<?php
        $mess="";

        if (isset($_GET['pageno'])) {
            $pageno = $_GET['pageno'];
        } else {
            $pageno = 1;
        }
        $no_of_records_per_page = 10;
        $offset = ($pageno-1) * $no_of_records_per_page;

        $db = mysqli_connect('localhost', 'root','','shopping_cart');
        // Check connection
        if (mysqli_connect_errno()){
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
            die();
        }

        $total_pages_sql = "SELECT COUNT(*) FROM products";
        $result = mysqli_query($db,$total_pages_sql);
        $total_rows = mysqli_fetch_array($result)[0];
        $total_pages = ceil($total_rows / $no_of_records_per_page);

        $sql = "SELECT * FROM products LIMIT $offset, $no_of_records_per_page";
        $res_data = mysqli_query($db,$sql);
        while($row = mysqli_fetch_array($res_data)){
            ?>
            <div class="w3-cell-row"  style="text-align:center;margin:15px;">

            <div class="w3-card-4 w3-cell" style="width:40%;margin-right:20px;margin-top:20px;margin-bottom:20px;align-content:center;">
            <h4 class="text-center"><?php echo $mess; ?></h4>
                
                <header class="w3-container w3-blue">
                <h1>  <?php echo $row['name'] ?></h1>
                </header>

                <div class="w3-container">
                <p> Description: <?php echo $row['description'] ?></p>
                <h5> Price: $<?php echo $row['price'] ?>
                
                </div>

                <footer class="w3-container ">
                <form  action="products.php" method="POST">
                <input type="hidden"name='product_id' value="<?php echo $row['product_id']?>">
                <input type="hidden"name='price' value="<?php echo $row['price']?>">
                <!-- <input type="submit"name='edit_product' style="width:40%" value="Edit Product"> -->
                <a href="editproducts.php?product_id=<?php echo $row['product_id']?>">Edit Product</a>
                <input type="submit"name='add_to_cart'  style="width:40%" value="Add to Cart">
                </form>
                </footer>
            </div>
            </div>
<?php
        }
        mysqli_close($db);
    ?>
    <ul class="pagination" style=" margin: auto; width: 50%; padding: 10px;">
        <li style=" margin: 10px;"><a href="?pageno=1">First</a></li>
        <li style=" margin: 10px;"class="<?php if($pageno <= 1){ echo 'disabled'; } ?>">
            <a href="<?php if($pageno <= 1){ echo '#'; } else { echo "?pageno=".($pageno - 1); } ?>">Prev</a>
        </li>
        <li style=" margin: 10px;" class="<?php if($pageno >= $total_pages){ echo 'disabled'; } ?>">
            <a href="<?php if($pageno >= $total_pages){ echo '#'; } else { echo "?pageno=".($pageno + 1); } ?>">Next</a>
        </li>
        <li style=" margin: 10px;"><a href="?pageno=<?php echo $total_pages; ?>">Last</a></li>
    </ul> 

<?php
$db = mysqli_connect('localhost', 'root','','shopping_cart');
echo "oooio";
if (isset($_POST["edit_product"])) {
  $product_id = $_POST["product_id"];
  echo "jjjhjhjh";
  header("location: editproducts.php?product_id=$product_id");
}

if (isset($_POST["add_to_cart"])) {
    $product_id = $_POST["product_id"];
    $email = $_SESSION["email"];
    $price = $_POST["price"];
    $quantity = 1;
    $user_id=$_SESSION['user_id'];
    echo $user_id;
    
    $select = "SELECT * FROM cart WHERE product_id = '$product_id' and `user_id` = '$user_id'";
    $select_query = mysqli_query($db, $select);
    if (!$select_query) {
      die(mysqli_error($db));
    //   ("<h5 style='text-align: center;'>Something went wrong</h5>");
    }
  
    if (mysqli_num_rows($select_query)) {
        echo 'if';
      $row = mysqli_fetch_assoc($select_query);
      $quantity += $row["quantity"];
      $update = "UPDATE cart SET quantity = {$quantity} WHERE id = {$row['id']}";
      $update_query = mysqli_query($db, $update);
  
      if (!$update_query) {
        die("<h5 style='text-align: center;'>Something went wrong</h5>");
      }
    } else {
               
        $query = "INSERT INTO cart (`product_id`, `quantity`, `user_id`,`price`) 
                VALUES('$product_id', '$quantity', '$user_id',$price)";  
        mysqli_query($db, $query);  
  
      if (!$query) {
          
        die("<h5 style='text-align: center;'>Something went wrong</h5>");
      }
    }
  
    $mess = "Item added to cart successfully";
  }


?>

</body>
</html>