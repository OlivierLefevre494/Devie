<?php
session_start();
include_once "../backend/databaseconn.php";
?>
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
    <meta charset="utf-8" />
    <script src="../javascript/gamesdisplay.js"></script>
    <script src="https://kit.fontawesome.com/7b2e0bb563.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Mitr&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@700&family=Ubuntu:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Mitr:wght@200&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=MuseoModerno:wght@700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@200&display=swap" rel="stylesheet">
    <link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/gamesdisplay.css"/>
    <title></title>
  </head>

  <body>
    <aside class="side-bar">

      <a href="home.php"><div class="messaging-link"></div></a>
      <a href="community.php"><i class="fa fa-users fa-2x" style="margin-left: 0.7vw; color: white;" aria-hidden="true"></i></a>
      <a href="games.php"><i class="fas fa-gamepad" style="margin-top: 1vh;margin-left: 0.5vw;font-size: 2.25em;color: white;" aria-hidden="true"></i></a>
    </aside>

    <p hidden id="game_id"><?php echo $_GET['id'];?></p>
    <header class="header">
      <h1 class="logobrand">DEVIE</h1>
      <div class="dropdown">
        <div class="avatar" id="user-avatar"></div>
        <div class="avatar-dropdown">
          <p onclick="profile()">Profile</p>
          <p onclick="friends()">Friends</p>
          <p onclick="logout()">Logout</p>
        </div>
      </div>
    </header>

    <main id="mainpage" class="mainpage" onscroll="onscrollfunction()">
      <a id="return-to-top" onclick="backToTop()"><i class="icon-arrow-up " id="back_to_top_btn"></i></a>




      <div class="game" id="searchresults">
        <div class="sidegames" id="sidegames">
        </div>

      </div>
      <aside class="recommendgames" id="recommendgames">

      </aside>

      </main>

  </body>
</html>
