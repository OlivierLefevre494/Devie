<?php

if (isset($_POST['reset-pwd-submit'])) {
  $selector = $_POST['selector'];
  $validator = $_POST['validator'];
  $pwd = $_POST['pwd'];
  $pwdrepeat = $_POST['pwd-repeat'];

  if (empty($pwd) || empty($pwdrepeat)) {
    header("Location: resetpassword.php?emptyfield");
    exit();
  } else if($pwd != $pwdrepeat)
    header("Location: resetpassword.php?emptyfield");
    exit();
  }

  $currentdate = date("U");

  include "databaseconn.php";

  $sql = "SELECT * FROM pwdReset WHERE pwdResetSelector=? AND pwdResetExpires >= ? ";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    echo "There was an error.";
    exit()
  } else {
    mysqli_stmt_bind_param($stmt, "s", $selector, $currentdate);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    if (!$row = mysqli_fetch_assoc($result)) {
      echo "You need to re-submit your reset request!";
      exit();
    } else {

      $tokenBin = hex2bin($validator);
      $tokencheck = password_verify($tokenBin, $row["pwdResetToken"]);

      if ($tokencheck === false) {
        echo "You need to re-submit your reset request!";
        exit();
      } elseif ($tokencheck === true) {


        $tokenusername = $row['pwdResetUsername'];

        $sql = "SELECT * FROM users WHERE username=?";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
          echo "There was an error.";
          exit();
        } else {
          mysqli_stmt_bind_param($stmt, "s", $tokenusername);
          mysqli_stmt_execute($stmt);
          $result = mysqli_stmt_get_result($stmt);
          if (!$row = mysqli_fetch_assoc($result)) {
            echo "There was an error!";
            exit();
          } else {

            $sql = "UPDATE users SET password=? WHERE username=? "

            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
              echo "There was an error.";
              exit();
            } else {
              $newpwdhash = password_hash($pwd, PASSWORD_DEFAULT)
              mysqli_stmt_bind_param($stmt, "ss", $newpwdhash, $tokenusername);
              mysqli_stmt_execute($stmt);

              $sql = "DELETE FROM pwdReset WHERE pwdResetUsername=?";
              $stmt = mysqli_stmt_init($conn);
              if (!mysqli_stmt_prepare($stmt, $sql)) {
                echo "There was an error!";
                exit();
              } else {
                mysqli_stmt_bind_param($stmt, "s", $tokenusername);
                mysqli_stmt_execute($stmt);
                header("Location: login.php?password=reset");
              }

          }


        }








      }

    }
  } else {
  header("Location: indexphp");
  exit();
}







 ?>
