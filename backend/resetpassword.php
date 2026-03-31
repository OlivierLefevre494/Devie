<?php

if (isset($_POST['reset-password-submit'])) {
$username = $_POST['username'];
include '../backend/databaseconn.php';
  if (empty($username)) {
    header("Location: ../frontend/forgotpassword.php?emptyfield");
    exit();
  } else {
    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $sql);
    $resultcheck = mysqli_num_rows($result);

    if ($resultcheck < 1) {
       header("Location: ../frontend/forgotpassword.php.php?nouser");
       exit();
    } else {
      $selector = bin2hex(random_bytes(8));
      $token = random_bytes(32);

      $url = "thedevie.com/resetpassword.php?selector=" . $selector . "&validator=" . bin2hex($token);

      $expires = date("U") + 3600;

      //CREATE TABLE `pwdReset` (
        //pwdResetId int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
        //pwdResetUsername TEXT NOT NULL,
        //pwdResetSelector TEXT NOT NULL,
        //pwdResetToken LONGTEXT NOT NULL,
        //pwdResetExpires TEXT NOT NULL
      // )


      $sql = "DELETE FROM pwdReset WHERE pwdResetUsername =?";
      $stmt = mysqli_stmt_init($conn);
      if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo "There was an error.";
        exit();
      } else {
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $sql = "INSERT INTO pwdReset (pwdResetUsername, pwdResetSelector, pwdResetToken, pwdResetExpires) VALUES (?, ?, ?, ?)";


        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
          echo "There was an error.";
          exit();
        } else {
          $hashedToken = password_hash($token, PASSWORD_DEFAULT);
          mysqli_stmt_bind_param($stmt, "ssss", $username, $selector, $hashedToken, $expires);
          mysqli_stmt_execute($stmt);

      }
          mysqli_stmt_close($stmt);
          mysqli_close($conn);

          $sql = "SELECT * FROM users WHERE username=?";
          $stmt = mysqli_stmt_init($conn);
          if (!mysqli_stmt_prepare($stmt, $sql)) {
            echo "There was a problem, please try again";
          } else {
            mysqli_stmt_bind_param($stmt, "i", $username);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
          }
          while ($row = $result->fetch_assoc()) {
            $email = $row['email'];
        }


          $to = ($email);

          $subject = "Password reset";

          $message = "<p>We recieved a password reset request. The link to your password reset is in the message. If you did not make this request, you can ignore this email.</p>";
          $message .= "<p> Here is your reset password link : <br>";
          $message .= "<a href=". $url . "> . $url . <a/> </p>";

          $headers = "From: Devie <thedevie.com@gmail.com\r\n";
          $headers .= "Reply-To: thedevie.com@gmail.com\r\n";
          $headers .= "Content-type: type/html\r\n";

          mail($to, $subject, $message, $headers);
          header("Location: ../frontend/forgotpassword.php?reset=success");
    }
  }
}
} else {
  header("Location: ../index.php");
}















 ?>
