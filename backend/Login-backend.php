<?php

session_start();


if (isset($_POST['submit'])) {
  include_once '../backend/databaseconn.php';

  $username = mysqli_real_escape_string($conn, $_POST['username']);
  $password = mysqli_real_escape_string($conn, $_POST['password']);

  //checking for empty fields
  if (empty($username) || empty($password)) {
    header("Location: ../frontend/login.php?login=empty");
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
    if ($resultcheck < 1) {
      header("Location: ../frontend/login.php?login=error");
      exit();
    } else {
      if ($row = mysqli_fetch_assoc($result)) {
        //dehashing the password
        $hashedpwdCheck = password_verify($password, $row['password']);
        if ($hashedpwdCheck == false) {
          header("Location: ../frontend/login.php?login=error");
          exit();
        } elseif ($hashedpwdCheck == true) {

          setcookie(
        "token",
        $row['token'],
        time() + (10 * 365 * 24 * 60 * 60), "/");

          //Logging the user in
          $_SESSION['user-id']       = $row['id'];
          $_SESSION['user-first']    = $row['first'];
          $_SESSION['user-last']     = $row['last'];
          $_SESSION['user-email']    = $row['email'];
          $_SESSION['user-username'] = $row['username'];
          $_SESSION['logged-in'] = true;
          $_SESSION['avatar'] = $row['avatar'];
          header("Location: ../frontend/home.php?login=success");
          exit();
        }
      }
    }
  }
} else {
   header("Location: ../frontend/login.php?login=error");
 }

if (isset($_POST['logout'])) {
  session_destroy();
  header("Location: ../frontend/login.php");
}








 ?>
