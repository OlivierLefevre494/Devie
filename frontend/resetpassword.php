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
    <link rel="stylesheet" href="../css/resetpassword.css">
    <link href="https://fonts.googleapis.com/css2?family=MuseoModerno:wght@700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@200&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@700&family=Ubuntu:wght@300;400;500;700&display=swap" rel="stylesheet">
    <meta charset="utf-8">
    <title>Reset password</title>
  </head>
  <body>

    <?php
    if ($_GET['selector'] !== null && $_GET['validator'] !== null) {
      $selector = $_GET['selector'];
      $validator = $_GET['validator'];


      if (empty($selector) || empty($validator)) {
        echo "We could not validate your request!";
      } else {

        if (ctype_xdigit($selector) !== false && ctype_xdigit($validator) !== false) {
          ?>
          <header class="header">
            <h1 class="logobrand">DEVIE</h1>
          </header>
          <div class="form">
            <form action="../backend/newpasswordchecker.php" method="post">
              <label class="label" for="newpwd" class="newpwd">New password</label>
              <input class="input" type="hidden" name="selector" value="<?php echo $selector; ?>">
              <input class="input" type="hidden" name="validator" value="<?php echo $validator; ?>">
              <input class="input" type="password" name="pwd" id="newpwd" placeholder="Enter a new password...">
              <input class="input" type="password" name="pwd-repeat" placeholder="Re-enter new password...">
              <button type="submit" name="reset-pwd-submit">Reset password</button>

            </form>
          </div>

          <?php

        }

      }
    } else {
      echo "We could not validate your request!";
    }


     ?>


  </body>
</html>
