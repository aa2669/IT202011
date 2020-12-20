<?php 
session_start();
 $db = mysqli_connect('localhost', 'root','','shopping_cart');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Document</title>
</head>
<body>
<?php 
require_once('userheader.php');
?>
    
<?php
$old_password = "";
$password2 = "";
$password3 = "";
$mess = "";
if (isset($_SESSION['username'])) {

  if (isset($_POST['reset'])) {

    $old_password = $_POST['old_password'];
    $password2 = $_POST['password2'];
    $password3 = $_POST['password3'];

    if (!empty($old_password) && !empty($password2) && !empty($password3)) {
      if (!password_verify($old_password, $_SESSION["password"])) {
        $mess = " invalid old password";
      } else {
        if (strlen($password2) < 3) {

        } else if ($password2 != $password3) {
          $mess = "New Password and Current Password does not match";
        } else {
          $hash_password =
            password_hash($password2,PASSWORD_DEFAULT);
          $update = "UPDATE users SET `password` = '{$hash_password}' WHERE user_id = {$_SESSION["user_id"]}";
          $update_query = mysqli_query($db, $update);

          if (!$update_query) {
            $mess = "Some went wrong again. Try again later";
          } else {
            $_SESSION["password"] = $hash_password;
            header("Location: userprofile.php");
          }
        }
      }
    } else {
      $mess = "Fields cannot be empty";
    }
  } else {
    $query = "SELECT * FROM users WHERE username = '{$_SESSION['username']}'";
    $select_user_query = mysqli_query($db, $query);

    if (!$select_user_query) {

      die("<h5 style='text-align: center;'>Something went wrong</h5>");
    }

    while ($row = mysqli_fetch_array($select_user_query)) {
      $username = $row['username'];
      $email = $row['email'];
    }
  }
} else {
  $mess = "User not logged in";
}

?>

<div class="container">
  <div class="row">
    <div class="col-xs-6 col-xs-offset-3">
      <div class="form-wrap">
        <h1>Reset Password</h1>
        <form role="form" action="resetpassword.php" method="post" id="login-form" autocomplete="off">
          <h5 class="text-center"><?php echo $mess; ?></h5>
          <div style="margin:20px;">
            <h5>Old Password:</h5>
            <input type="password" name="old_password" id="old_password" class="form-control"
              value="<?php echo htmlspecialchars($old_password); ?>" placeholder="Current Password">
          </div>
          <div style="margin:20px;">
          <h5>New  Password:</h5>      
          <input type="password" name="password2" id="password2" class="form-control"
              value="<?php echo htmlspecialchars($password2); ?>" placeholder=" New Password">
          </div>
          <div style="margin:20px;">
          <h5>Re-enter Password:</h5>
            <input type="password" name="password3" id="password3" class="form-control"
              value="<?php echo htmlspecialchars($password3); ?>" placeholder="Re-enter Password">
          </div>

          <input type="submit" name="reset"  value="Reset ">
        </form>

      </div>
    </div>
  </div>
</div>
</div>
</body>
</html>