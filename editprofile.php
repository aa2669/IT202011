<?php 
session_start();
$db = mysqli_connect('localhost', 'aa2669','nkP1JzTbcM9FN1eu','aa2669');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>edit-profile</title>
</head>
<body>
    


<?php
$username = "";
$email = "";
$mess = "";
if (isset($_SESSION['username'])) {

  if (isset($_POST['edit'])) {

    $username = $_POST['username'];
    $email = $_POST['email'];

    if (!empty($username) && !empty($email)) {
        $username = mysqli_real_escape_string($db, $username);
        $email = mysqli_real_escape_string($db, $email);
      $query = "SELECT * FROM users WHERE username = '{$username}' and username != '{$_SESSION['username']}'";
      $user_query = mysqli_query($db, $query);

      if (!$user_query) {
die(mysqli_error($db));
        //die("<h5 style='text-align: center;'>Something went wrong</h5>");
      }

      if (mysqli_num_rows($user_query)) {
        $mess = "Username already taken";
      } else {
        $query =
          "SELECT * FROM users WHERE email = '{$email}' and email != '{$_SESSION['email']}'";
        $query_query = mysqli_query($db, $query);

        if (mysqli_num_rows($query_query)) {
          $mess = "Email already taken";
        } else {
          $update = "UPDATE users SET email = '{$email}', username = '{$username}' WHERE user_id = {$_SESSION['user_id']}";
          $query = mysqli_query($db, $update);
          if (!$query) {

            die("<h5 style='text-align: center;'>Something went wrong</h5>");
            // die(mysqli_error($db));
          }
          $_SESSION['username'] = $username;
          $_SESSION['email'] = $email;
          header("Location: userprofile.php");
        }
      }
    } else {
      $mess = "All Fields are mandatory";
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

<div class="container" >
  <div >
    <div>
     
        <h1 style="margin:20px;">Edit Profile</h1>
        <form role="form" action="editprofile.php" method="post" autocomplete="off">
          <h5 class="text-center"><?php echo $mess; ?></h5>
          <div style="margin:20px;">
            <label for="username" style="text-size:20px;font-size:20px;" >Username:</label>
            <input type="text" name="username" id="username" class="form-control"
              value="<?php echo htmlspecialchars($username); ?>" placeholder="username">
          </div>
          <div style="margin:20px;" >
            <label for="email" style="text-size:20px;font-size:20px;" class="sr-only">Email: </label>
            <input type="email" name="email" id="email" class="form-control"
              value="<?php echo $email; ?>" placeholder="email">
          </div>

          <input style="text-size:20px;font-size:20px;margin:20px;color:red;" type="submit" name="edit"  value="Edit Profile">
        </form>

     
    </div>
  </div>
</div>
</div>

</body>
</html>