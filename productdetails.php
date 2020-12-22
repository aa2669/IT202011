
<?php
session_start();
$db = mysqli_connect('localhost', 'aa2669','nkP1JzTbcM9FN1eu','aa2669');

$message = "";
$select = "SELECT * FROM products WHERE product_id = {$_GET['product_id']}";
$select_query = mysqli_query($db, $select);
if (!$select_query) {
  die("<h5 style='text-align: center;'>Something went wrong</h5>");
}
while ($row = mysqli_fetch_assoc($select_query)) {
  $id = $row["product_id"];
  $name = $row["name"];
  $quantity = $row["quantity"];
  $price = $row["price"];
  $description = $row["description"];
  $user_id = $row["user_id"];
  $category = $row["category"];
  $visibility = $row["visibility"];
}

if (isset($_POST["add-to-cart"])) {
  $product_id = $_POST["product_id"];
  $user_id = $_SESSION["user_id"];
  $price = $_POST["price"];
  $quantity = $_POST["quantity"];

  $select = "SELECT * FROM cart WHERE product_id = {$product_id} and `user_id` = {$user_id}";
  $select_query = mysqli_query($db, $select);
  if (!$select_query) {
    die("<h5 style='text-align: center;'>Something went wrong</h5>");
  }

  if (mysqli_num_rows($select_query)) {
    $row = mysqli_fetch_assoc($select_query);
    $quantity += $row["quantity"];
    $update = "UPDATE cart SET quantity = {$quantity} WHERE id = {$row['id']}";
    $update_query = mysqli_query($db, $update);

    if (!$update_query) {
      die("<h5 style='text-align: center;'>Something went wrong</h5>");
    }
  } else {
    $insert = "INSERT INTO cart (product_id, quantity, `user_id`, price) ";
    $insert .= "VALUES ({$product_id}, {$quantity}, {$user_id}, {$price})";
    $insert_query = mysqli_query($db, $insert);

    if (!$insert_query) {
      die("<h5 style='text-align: center;'>Something went wrong</h5>");
    }
  }

  $message = "Item added to cart successfully";
}

?>

<div class="container">
  <section id="product_detail">
    <div class="container">
      <h5 class="text-center"><?php echo $message; ?></h5>
      <div class="row">
        <div class="col-xs-6 col-xs-offset-3">
          <div class="form-wrap">
            <h1>Product Detail</h1>
            <h3 class="card-title"><?php echo $name ?></h3>
            <h5 class="card-subtitle mb-2 text-muted"><?php echo $category ?></h5>
            <h4>$ <?php echo $price ?></h4>
            <p class="card-text"><span style="font-weight: bold;">Description:</span> <?php echo $description ?></p>

            <form style="display:inline-block;" role="form"
              action="<?php echo $base_url . '/product_details.php?product_id=' . $id; ?>" method="post" id="login-form"
              autocomplete="off">

              <p>Quantity</p>
              <div class="form-group">
                <input style="width:7rem;" type="number" min="1" max="<?php echo $quantity ?>" value="<?php echo $quantity ?>" name="quantity"
                  class="form-control" placeholder="Quantity">
</div>
              
              
            </form>
            
   
          </div>
        </div>
      </div>
    </div>
  </section>

  <hr />

 
