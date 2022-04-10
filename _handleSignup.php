<?php
$showError = "false";
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    include '_db_connect.php';
    $email = $_POST['signupEmail'];
    $password = $_POST['signupPassword'];
    $cpassword = $_POST['signupcPassword'];

    $existSql = "SELECT * FROM `users` WHERE user_email = '$email'";
    $result = mysqli_query($conn, $existSql);
    $numExistsRows = mysqli_num_rows($result);
    if($numExistsRows > 0){
      $showError = "Email Already Exits";
    }
    else{
      if($password == $cpassword){
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO `users` (`user_email`, `user_pass`, `timestamp`) VALUES ('$email', '$hash', current_timestamp())";
        $result = mysqli_query($conn, $sql);
        if($result){
          $showAlert = true;
          header("Location: /index.php?signupsuccess=true");
          exit();
        }
      }
      else{
        $showError = "Password do not match";
      }
    }
    header("Location: /index.php?signupsuccess=false&error=$showError");
}
?>