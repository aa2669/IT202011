<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
</head>
<body>
    
<?php
require_once("nav.php");
$db = mysqli_connect('localhost', 'aa2669','nkP1JzTbcM9FN1eu','aa2669');


$old_password = "";
$password2 = "";
$password3 = "";
$mess = "";

  if (isset($_POST['reset'])) {

    $username = $_POST['username'];
    $password2 = $_POST['password2'];
    $password3 = $_POST['password3'];
    $user_check_query = "SELECT * FROM users WHERE username='$username' ";
    $result = mysqli_query($db, $user_check_query);
    if(mysqli_num_rows($result) > 0)  {

    if (!empty($username) && !empty($password2) && !empty($password3)) {
            if ($password2 != $password3) {

          $mess = "New Password and Current Password does not match";
        } else {
          $hash_password =
            password_hash($password2,PASSWORD_DEFAULT);
          $update = "UPDATE users SET `password` = '{$hash_password}' WHERE username = '$username'";
          $update_query = mysqli_query($db, $update);

          if (!$update_query) {
            die(mysqli_error($db));

            $mess = "Some went wrong again. Try again later";
          } else {
            $_SESSION["password"] = $hash_password;
            header("Location: login.php");
          }
        }
      }
    } else {
      $mess = "Fields cannot be empty";
    }
}
   

?>


    

<div class="container">
  <div class="row">
    <div class="col-xs-6 col-xs-offset-3">
      <div class="form-wrap">
        <h1>Reset Password</h1>
        <form role="form" action="forgotpassword.php" method="post" id="login-form" autocomplete="off">
          <h5 class="text-center"><?php echo $mess; ?></h5>
          <div style="margin:20px;">
            <h5>Username:</h5>
            <input type="text" name="username" id="username" class="form-control"
              value="" placeholder="Username">
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