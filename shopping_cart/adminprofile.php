<?php 
session_start();
 $db = mysqli_connect('localhost', 'root','','shopping_cart');
?>

<?php
$username = "";
$email = "";
$mess = "";
if (isset($_SESSION['username'])) {
  $query = "SELECT * FROM users WHERE username = '{$_SESSION['username']}'";
  $user_query = mysqli_query($db, $query);

  if (!$user_query) {

    die("<h5 style='text-align: center;'>Something went wrong</h5>");
  }

  while ($row = mysqli_fetch_array($user_query)) {
    $username = $row['username'];
    $email = $row['email'];
  }
} else {
  $mess = "not logged in";
}

?>
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

<div style="align-content:center;text-align:center;margin-top:50px;"class="container">
  <div style="margin:10px;">
    <div style="margin:10px;">
      <h1 style="margin:10px;color:red;">User Profile</h1>
      <h4 style="margin:10px;" class="text-center"><?php echo $mess; ?></h4>
      <h4 style="margin:10px;"><span class="font-weight-bold">Username: </span><span><?php echo ($username); ?> </span></h3>
        <h4><span class="font-weight-bold">Email: </span><span><?php echo ($email); ?> </span></h3>
          <!-- <button class="btn btn-default"><a href="editprofile.php">Edit Profile</a></button>
          <button class="btn btn-default"><a href="resetpassword.php">Reset Password</a></button> -->
    </div>
  </div>
</div>
</div>

</body>