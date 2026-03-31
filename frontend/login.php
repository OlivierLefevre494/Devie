<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-196856891-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-196856891-1');
</script>

<!DOCTYPE html>
<html>
  <head>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@700&family=Ubuntu:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@200&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=MuseoModerno:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/login.css">
    <meta charset="utf-8">
    <title>Login</title>
  </head>
  <body class="body">
    <ul class='circles'>
   <li></li>
   <li></li>
   <li></li>
   <li></li>
   <li></li>
   <li></li>
   <li></li>
   <li></li>
   <li></li>
   <li></li>
   </ul>
    <header class="login-header">
      <h1 class="Logobrand">DEVIE</h1>
      <a href="accountcreation.php" class="createaccountlink"> Don't have an account yet? Create one! </a>
    </header>
    <div class="loginform">
      <form class="form" action="../backend/Login-backend.php" method="post">
        <label for="signup-title" class="signup-title">Login</label>
        <input type="text" name="username" class="username" id="signup-title" placeholder="Username...">
        <input type="password" name="password" class="password" placeholder="Password...">
        <input type="submit" name="submit" class="submission" value="Login">
        <a href="forgotpassword.php" class="forgotpass-link">Forgot your password?</a>
      </form>
      <?php
      $fullURL = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
      if (strpos($fullURL, "login=empty") == true) {
        echo "<p style='color: red; text-align: center;'>You left one of the fields empty!</p>";
      } elseif (strpos($fullURL, "login=error") == true) {
        echo "<p style='color: red; text-align: center;'>Either the username or the password was invalid!</p>";
      } elseif (strpos($fullURL, "login=password=reset") == true) {
        echo "<p style='color: green; text-align: center;'>Your password has been reset</p>";
      }

       ?>
    </div>
  </body>
</html>
