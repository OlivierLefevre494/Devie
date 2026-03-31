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
    <link rel="stylesheet" href="../css/accountcreation.css">
    <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@700&family=Ubuntu:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=MuseoModerno:wght@700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@200&display=swap" rel="stylesheet">
    <meta charset="utf-8">
    <title>Create account</title>
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

   </header>
   <div class="createaccountform">
     <form class="form" action="../backend/userinfo.php" method="post">
       <label for="signup-title" class="createaccounttitle">Create your account!</label>
       <input type="text" name="email" id="signup-title" class="username" placeholder="Email...">
       <input type="text" name="username"  class="username" placeholder="Your Devie username...">
       <input type="password" name="password" class="password" placeholder="Password (a number and a capital letter)">
       <input type="password" name="repassword" class="password" placeholder="Re-enter your password">
       <input type="submit" name="submitbutton" class="submission" value="Create Account">
     </form>
     <?php
     #TODO keep the info depending on the stuff.
     #TODO forgot password
     $fullURL = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
     if (strpos($fullURL, "emptyfield") == true) {
       echo "<p style='color: red; text-align: center;'>You left one of the fields empty!</p>";
     } else {
       if (strpos($fullURL, "characters") == true)  {
         echo "<p style='color: red; text-align: center;'>You used invalid characters!</p>";
       } else {
         if (strpos($fullURL, "emaill") == true) {
       echo "<p style='color: red; text-align: center;'>Your email is invalid!</p>";
      } else {
         if (strpos($fullURL, "username=taken") == true) {
       echo "<p style='color: red; text-align: center;'>This username is taken</p>";
      } else {
        if (strpos($fullURL, "email=taken") == true) {
       echo "<p style='color: red; text-align: center;'>This email is already taken</p>";
      } else {
        if (strpos($fullURL, "re-entered_password_invalid") == true) {
       echo "<p style='color: red; text-align: center;'>The password your re-entered password is invalid!</p>";
     }
     }
     }
     }
     }
     }
     ?>
   </div>
  </body>
</html>
