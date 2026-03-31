<?php
error_reporting(E_ALL);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
if (isset($_POST['submitbutton'])) {

  include_once '../backend/databaseconn.php';

  $email = mysqli_real_escape_string($conn, $_POST['email']);
  $username = mysqli_real_escape_string($conn, $_POST['username']);
  $password = mysqli_real_escape_string($conn, $_POST['password']);
  $repassword = mysqli_real_escape_string($conn, $_POST['repassword']);

  //error handlers
  #print("[$first] [$last] [$email] [$username] [$password]");
  #exit();

  if (empty($email) || empty($username) || empty($password) || empty($repassword)) {
    header("Location: ../frontend/accountcreation.php?emptyfield");
    exit();
  } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
     header("Location: ../frontend/accountcreation.php?emaill");
     exit();

  } else {
     $sql = "SELECT * FROM users WHERE username=?";
     $stmt = mysqli_stmt_init($conn);
     if (!mysqli_stmt_prepare($stmt, $sql)) {
       echo "There was a problem, please try again";
     } else {
       mysqli_stmt_bind_param($stmt, "s", $username);
       mysqli_stmt_execute($stmt);
       $result = mysqli_stmt_get_result($stmt);
     }
     $resultcheck = mysqli_num_rows($result);

     if ($resultcheck > 0) {
        header("Location: ../frontend/accountcreation.php?username=taken");
        exit();
     } else {
       $sql = "SELECT * FROM users WHERE email=?";
       $stmt = mysqli_stmt_init($conn);
       if (!mysqli_stmt_prepare($stmt, $sql)) {
         echo "There was a problem, please try again";
       } else {
         mysqli_stmt_bind_param($stmt, "s", $email);
         mysqli_stmt_execute($stmt);
         $result = mysqli_stmt_get_result($stmt);
       }
       $resultcheck = mysqli_num_rows($result);
       if ($resultcheck > 0) {
          header("Location: ../frontend/accountcreation.php?email=taken");
          exit();
     } else {
       if ($password !== $repassword) {
         header("Location: ../frontend/accountcreation.php?re-entered_password_invalid");
         exit();
     } else {
       function createtoken() {
         include_once '../backend/databaseconn.php';
         $token = openssl_random_pseudo_bytes(32);

         //Convert the binary data into hexadecimal representation.
         $token = bin2hex($token);

         $sql = "SELECT * FROM users WHERE token=?";
         $stmt = mysqli_stmt_init($conn);
         if (!mysqli_stmt_prepare($stmt, $sql)) {
         } else {
           mysqli_stmt_bind_param($stmt, "s", $token);
           mysqli_stmt_execute($stmt);
           $result = mysqli_stmt_get_result($stmt);
         }
         $resultcheck = mysqli_num_rows($result);

         if ($resultcheck == 0) {
           return $token;
         } else {
           createtoken();
         }
       }
       $token = createtoken();
       $bio = "This is your bio! Click on the pencil in the bottom right of this box to change your bio!";
       //Generate a random string.
        //hashing the password
        $hashedpwd = password_hash($password, PASSWORD_DEFAULT);
        //insert the user into the database
        $avatar = "../images/blankprofilepicture.png";
        $sql = "INSERT INTO users (username, token, password, avatar, email, bio) VALUES (?, ?, ?, ?, ?, ?);";

        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
          echo "There was a problem, please try again1";
        } else {
          mysqli_stmt_bind_param($stmt, "ssssss", $username, $token, $hashedpwd, $avatar, $email, $bio);
          mysqli_stmt_execute($stmt);
          $result = mysqli_stmt_get_result($stmt);
          echo $result;
          header("Location: ../frontend/login.php?success");
        }
     }
  }
}
}
} else {
  header("Location: ../frontend/accountcreation.php");
  exit();
}













 ?>
