<?php
session_start();
require_once("nav.php"); ?>
<form  action="login.php" method="POST">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required/>
    <label for="p1">Password:</label>
    <input type="password" id="p1" name="password" required/>
    <input type="submit" name="login" value="Login"/>
</form>

<?php

if (isset($_POST['login'])) {
    $db = mysqli_connect('localhost', 'root','','shopping_cart');
     
    $email =  $_POST['email'];
    $password_1 =  $_POST['password'];  
       $user_check_query = "SELECT * FROM users WHERE email='$email' ";
       $result = mysqli_query($db, $user_check_query);
       
      if(mysqli_num_rows($result) > 0)  
       {  
            while($row = mysqli_fetch_array($result))  
            {  
               
                 if(password_verify($password_1, $row["password"]))  
                 {  
                     if(strcmp($row['email'], 'admin@gmail.com') == 0  )
                     {
                        $_SESSION["email"] = $email; 
                        $_SESSION["user_id"] = $row['user_id']; 
                        $_SESSION["username"] = $row['username']; 
                        $_SESSION["password"] = $row['password']; 
                        $_SESSION['success'] = "You are now logged in";
                         header('location: adminhome.php');
                     }
                    else
                    {
                      $_SESSION["email"] = $email; 
                      $_SESSION["user_id"] = $row['user_id']; 
                      $_SESSION["username"] = $row['username']; 
                      $_SESSION["password"] = $row['password']; 

                      $_SESSION['success'] = "You are now logged in";
                       
                      header("location:userhome.php");  
                 }
                  }

                 else  
                 {  
                      //return false;  
                      echo "Wrong User Details";  
                 }  
            }  
       }  
    //    else if(strcmp($email, 'admin@gmail.com') == 0  && strcmp( $password_1, 'admin') == 0)
    //     {
    //         $_SESSION["email"] = $email; 
    //         $_SESSION["user_id"] = $row['user_id']; 
    //         $_SESSION["username"] = $row['username']; 
    //         $_SESSION["password"] = $row['password']; 
    //         $_SESSION['success'] = "You are now logged in";
    //          header('location: adminhome.php');
    //     }
        else
        {
            echo '<center><div style="margin-top: 30px; margin-bottom: 10px; font-size: 20px; color: red; font-weight: bold;"> Incorrect username or password</div></center>';
        
        }
}
     
             
 
?>
