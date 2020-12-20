<?php 
session_start();
$user_id=$_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

</head>
<body>
<?php

require_once('adminheader.php');
?>

<form  style="margin-top:20px;"action="addproducts.php" method="POST">
    <label for="name">Name:</label>
    <br>
    <input type="text" id="name" name="name" required />
    <label for="description">Description:</label>
    <input type="text" id="description" name="description" required maxlength="60"/>
    <label for="p1">Category:</label>
    <br>
    <select style="margin-left:10px;width:98%;"name="category" id="category" style="width:20%;">
    <option value="Electronics">Electronics</option>
    <option value="Clothing">Clothing</option>
    <option value="Footwear">Footwear</option>
    <option value="Grocery">Grocery</option>
    <option value="Toys">Toys</option>
    
    </select>
    <br>
    <label for="p2">Quantity:</label>
    <input type="number" id="quantity" name="quantity" required/>
    <label for="p2">Price:</label>
    <input type="number" id="price" name="price" required/>
    <label for="p2">visibility:</label>
    <select style="margin-left:10px;width:98%;" name="visibility" id="visibility" style="width:20%;">
    <br>
    <option value="true">Public</option>
    <option value="false">Private</option>
   
    
    </select>
    <input type="submit" name="add" value="Add Product"/>
</form>




<?php
if (isset($_POST["add"])) {

    $name = "";
    $description= "";
    
    $db = mysqli_connect('localhost', 'root','','shopping_cart');
    
    // Add Product 
      $name =  $_POST['name'];
      $description = $_POST['description'];
      $category =  $_POST['category'];
      $quantity = $_POST['quantity'];
      $price= $_POST['price'];
      $visibility= $_POST['visibility'];
       


      if (empty($name)||empty($description)||empty($category)||empty($quantity) ||empty($price))
    { 
    echo '<center><div style="margin-top: 30px; margin-bottom: 10px; font-size: 20px; color: red; font-weight: bold;"> All Fields are required</div></center>';
    }
    else
    {
        
        
  	$query = "INSERT INTO products (`name`,`description`,`category`,`quantity`,`price`,`visibility`,`user_id`) 
                VALUES('$name','$description','$category',$quantity,$price,$visibility,$user_id)";

    mysqli_query($db, $query);
    $_SESSION['email'] = $email;

    header('location: products.php');

    }
}
    ?>

</body>
</html>