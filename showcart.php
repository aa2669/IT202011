<?php 
session_start();
$db = mysqli_connect('localhost', 'aa2669','nkP1JzTbcM9FN1eu','aa2669');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <title>Cart</title>
</head>
<body>
<?php

require_once('adminheader.php');
?>

<?php
$cart = "SELECT DISTINCT p.name as name, p.category as category, p.product_id as product_id, c.quantity as quantity, p.quantity as p_quantity, c.price as price, c.id as id
       FROM cart as c INNER JOIN products as p ON c.product_id = p.product_id where c.user_id = {$_SESSION["user_id"]}";
$query = mysqli_query($db, $cart);
if (!$query) {
  die(mysqli_error($db));
    //   "<h5 style='text-align: center;'>Something went wrong</h5>");
}

if (mysqli_num_rows($query) === 0) {
  die("<h3 style='text-align: center;'>No Items in Cart</h3>");
} ?>


<?php
$mess = "";
if (isset($_POST["remove-item"])) {
  $id = $_POST["cart_id"];
  $user_id = $_SESSION["user_id"];

  $query = "DELETE FROM cart WHERE id = {$id} and `user_id` = {$user_id}";
  $delete_query = mysqli_query($db, $query);

  if (!$delete_query) {
    die("<h5 style='text-align: center;'>Something went wrong</h5>");
  }

  $mess = "Item removed from cart ";
  echo "<script type='text/javascript'>alert('$mess');</script>";
  header("Location: showcart.php");
}

if (isset($_POST["remove-all-item"])) {
  $user_id = $_SESSION["user_id"];

  $query = "DELETE FROM cart WHERE `user_id` = {$user_id}";
  $delete_query = mysqli_query($db, $query);

  if (!$delete_query) {
    die("<h5 style='text-align: center;'>Something went wrong</h5>");
  }

  $mess = "All cart items removed successfully";
  echo "<script type='text/javascript'>alert('$mess');</script>";
  header("Location: showcart.php");
}

?>

<div class="container">
  <div class="row">
    <h5 class="text-center"><?php echo $mess; ?></h5>
    <div class="col-6 col-md-12" style="border-right: 1px solid #ccc; text-align: center;">
    
     
      <hr />
      <?php
      $cart_value = 0;
      $item = 0;
      while ($row = mysqli_fetch_assoc($query)) {
        $name = $row["name"];
        $category = $row["category"];
        $quantity = $row["quantity"];
        $price = $row["price"];
        $id = $row["id"];
        $product_id = $row["product_id"];
        $p_quantity = $row["p_quantity"];
        $total = $quantity * $price;

        $cart_value += $total;
        $item += 1;
      ?>
      <div style="align-content:center;text-align:center;">
     
      
        <div class="card" style="width: 70%; margin-left:70px;text-align: center;">
	  <div class="card-body">
<a href="<?php echo 'productdetails.php?product_id=' . $product_id; ?>"   style="color: inherit; text-decoration: none;">
            <h5 ><?php echo $name ?></h5>
            <h6 ><?php echo $category ?></h6>
            <p style="display:inline-block; font-weight: bold;">Quantity: </p>
            <div class="form-group" style="display:inline-block;">
              <input style="width:7rem;" type="number" min="1" max="<?php echo $p_quantity ?>"
                value="<?php echo $quantity ?>" name="quantity" class="form-control" placeholder="Quantity">
            </div>
            <p class="card-text"><span style="font-weight: bold;">Total Price: </span>$ <?php echo $total; ?>
	    </p>
</a>
            <form name="cart_form" action="showcart.php" method="POST">
              <input type='hidden' name='cart_id' value="<?php echo $id ?>" />
              <input type="submit" name="remove-item" class="btn btn-danger" value="Remove Item">
            </form>
            

	  </div>
        </div>
      

      <?php } ?>


    </div>
    <form name="remove" action="showcart.php" method="POST">
        <input type="submit" name="remove-all-item" class="btn btn-danger" value="Remove All Cart Items">
      </form>
    <div style="text-align: center;margin-top:30px;">
      <h3 style="color:red;"> Total Value</h3>
      <hr />
      <h5 style="color:red;">Total Items :  <?php echo $item ?></h5>
      <h5 style="color:red;">Total Price: $<?php echo $cart_value ?></h5>

    </div>
     

      <hr />
      </div>
    
  </div>
</div>
<?php

if (isset($_POST["place-order"])) {
  echo 'clicked';
  header("location: payment.php?cart_value=" . urlencode(serialize($cart_value)));
}
?>

</body>
</html>
