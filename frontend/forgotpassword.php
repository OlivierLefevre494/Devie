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
    <meta charset="utf-8">
    <link href="https://fonts.googleapis.com/css2?family=MuseoModerno:wght@700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@200&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@700&family=Ubuntu:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/forgotpassword.css">
    <title>Forgot your password?</title>
  </head>
  <body>
    <header class="header">
      <h1 class="logobrand">DEVIE</h1>
    </header>
    <div class="form">
      <form action="../backend/resetpassword.php" method="post">
        <p class="message">An email will be sent to you with instructions to reset your password!</p>
        <input type="text" class="input" name="username" placeholder="Enter your username...">
        <input type="submit" class="submission" name="reset-password-submit" value="Send email">
        <?php
        $fullURL = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        if (strpos($fullURL, "emptyfield") == true) {
          echo "<p style='color: red; text-align:center;'>You left the field empty!</p>";
        } elseif (strpos($fullURL, "nouser") == true) {
          echo "<p style='color: red; text-align:center;'>No user exists with this username!</p>";
        } elseif (strpos($fullURL, "reset=success") == true) {
          echo "<p style='color: green; text-align:center;'>An email has been sent to you!</p>";
        }

         ?>
      </form>
    </div>
  </body>
</html>
