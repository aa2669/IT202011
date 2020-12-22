<?php

session_start();
$user_id=$_SESSION['user_id'] ;
$cart_value= unserialize(urldecode($_GET['cart_value']));

$addres="";
$db = mysqli_connect('localhost', 'aa2669','nkP1JzTbcM9FN1eu','aa2669');

if (isset($_POST['process_payment'])) {
    
    $address =  $_POST['address'];
    $payment_method=$_POST['payment_method'];
   
    $query = "INSERT INTO orders (`user_id`,`total_price`,`address`,`payment_method`) 
    VALUES('$user_id','$cart_value', '$address','$payment_method')";
     mysqli_query($db, $query);
    $user_id = $_SESSION["user_id"];
    
    $order_id = $db->insert_id;
    $get_cart_details = "SELECT DISTINCT p.name as name, p.category as category, p.product_id as product_id, c.quantity as quantity, p.quantity as p_quantity, c.price as price, c.id as id
       FROM cart as c INNER JOIN products as p ON c.product_id = p.product_id where c.user_id = {$user_id}";
    $get_cart_details_query = mysqli_query($db, $get_cart_details);
    if(! $get_cart_details_query)
    {
        die(mysqli_error($db));
    }
    $array_cart_items = array();
    $total_cart_value = 0;
    while ($row = mysqli_fetch_assoc($get_cart_details_query)) {
      array_push(
        $array_cart_items,
        array(
          "product_id" => $row['product_id'],
          "quantity" => $row['quantity'],
          "price" => $row['price'],
          "p_quantity" => $row["p_quantity"]
        )
      );
      $quantity = $row["quantity"];
      $price = $row["price"];
      $total_price = $quantity * $price;
      $total_cart_value += $total_price;
    }
    var_dump($array_cart_items);

    $order_item = "INSERT INTO orderitems(order_id, product_id, quantity, unitprice) VALUES";
 
    foreach ($array_cart_items as $row) {
      $p_id = $row["product_id"];
      $quantity = $row["quantity"];
      $price = $row["price"];
 
      $order_item .= " ({$order_id}, {$p_id}, {$quantity}, {$price}),";
    }
    $order_item = substr($order_item, 0, strlen($order_item) - 1);
    echo $order_item;
    $order_item_query = mysqli_query($db, $order_item);
 
    if (!$order_item_query) {
      die("<h5 style='text-align: center;'>Something went wrong</h5>");
    }

    $query = "DELETE FROM cart WHERE `user_id` = {$user_id}";
    $delete_query = mysqli_query($db, $query);
    if (!$delete_query) {
    die("<h5 style='text-align: center;'>Something went wrong</h5>");
    }

        header('location: userhome.php');
    }

 
?>

<!DOCTYPE html>
<html lang="en">
<head>
	
	<meta charset="utf-8">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="../styles.css">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link rel="stylesheet" type="text/css" href="../bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="../font-awesome.min.css" />

    <script type="text/javascript" src="../jquery-1.10.2.min.js"></script>
	<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</head>
<body>


<div class="container" style="margin-top:100px;">
<form  method="POST">

<div class="container">
    <div class="row">
        <div class="col-xs-12 col-md-4 col-md-offset-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="row">
                        <h3 class="text-center">Payment Details</h3>
                        <img class="img-responsive cc-img" src="http://prepbootstrap.com/Content/images/shared/misc/creditcardicons.png">
                    </div>
                </div>
                <div class="panel-body">
                    <form role="form">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label>CARD NUMBER</label>
                                    <div class="input-group">
                                        <input type="tel" class="form-control" placeholder="Valid Card Number" />
                                        <span class="input-group-addon"><span class="fa fa-credit-card"></span></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-7 col-md-7">
                                <div class="form-group">
                                    <label><span class="hidden-xs">EXPIRATION</span><span class="visible-xs-inline">EXP</span> DATE</label>
                                    <input type="tel" class="form-control" placeholder="MM / YY" />
                                </div>
                            </div>
                            <div class="col-xs-5 col-md-5 pull-right">
                                <div class="form-group">
                                    <label>CV CODE</label>
                                    <input type="tel" class="form-control" placeholder="CVC" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label>CARD OWNER</label>
                                    <input type="text" class="form-control" placeholder="Card Owner Names" />
                                </div>
                            </div>
						</div>
						<div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label>Total Price</label>
                                    <input type="text" class="form-control" placeholder="Card Owner Names" value = "<?php echo '$';echo $cart_value;?>" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label >Address</label>
                                    <input type="text" class="form-control" placeholder="Address" id="address" name="address"  required />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label >Payment Method</label>
                                    <select name="payment_method">
                                        <option value="COD">CashOnDelivery</option>
                                        <option value="debitcard">Debit Card</option>
                                        <option value="creditcard">Credit Card</option>
                                    </select> 
                                 </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="panel-footer">
                    <div class="row">
                        <div class="col-xs-12">
                            <button class="btn btn-warning btn-lg btn-block" name="process_payment">Process payment</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</form>
<style>
    .cc-img {
        margin: 0 auto;
    }
</style>

</div>
</body>
</html>