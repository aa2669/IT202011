<?php include('nav.php'); 

if (isset($_POST["register"])) {

$username = "";
$email    = "";

$db = mysqli_connect('localhost', 'root','','shopping_cart');

// REGISTER USER
  $username =  $_POST['username'];
  $email = $_POST['email'];
  $password_1 =  $_POST['password'];
  $confirm = $_POST["confirm"];

  if (empty($username)||empty($email)||empty($password_1)||empty($confirm))
   { 
    echo '<center><div style="margin-top: 30px; margin-bottom: 10px; font-size: 20px; color: red; font-weight: bold;"> All Fields are required</div></center>';
    }
  
  else if ($password_1 != $confirm)
   {
    echo '<center><div style="margin-top: 30px; margin-bottom: 10px; font-size: 20px; color: red; font-weight: bold;"> The two passwords do not match</div></center>';

    }

 else{
  $user_check_query = "SELECT * FROM users WHERE username='$username' OR email='$email' LIMIT 1";
  $result = mysqli_query($db, $user_check_query);
  $user = mysqli_fetch_assoc($result);
  
  if ($user) { // if user exists
    if ($user['username'] === $username) {
        echo '<center><div style="margin-top: 30px; margin-bottom: 10px; font-size: 20px; color: red; font-weight: bold;"> Username already exists</div></center>';

    }

   else if ($user['email'] === $email) {
        echo '<center><div style="margin-top: 30px; margin-bottom: 10px; font-size: 20px; color: red; font-weight: bold;"> email already exists</div></center>';
    }
  }

  else  {
    $hash = password_hash($password_1, PASSWORD_DEFAULT);

  	$query = "INSERT INTO users (`email`, `username`, `password`) 
                VALUES('$email', '$username', '$hash')";
   
    mysqli_query($db, $query);

      
  	$_SESSION['username'] = $username;
  	$_SESSION['success'] = "You are now logged in";
  	header('location: home.php');
  }
 }

 }

?>
<form  action="register.php" method="POST">
    <label for="email">Email:</label>
    <br>
    <input type="email" id="email" name="email" required />
    <label for="user">Username:</label>
    <input type="text" id="user" name="username" required maxlength="60"/>
    <label for="p1">Password:</label>
    <input type="password" id="p1" name="password" required/>
    <label for="p2">Confirm Password:</label>
    <input type="password" id="p2" name="confirm" required/>
    <input type="submit" name="register" value="Register"/>
</form>
