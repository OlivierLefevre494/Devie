<?php session_start();
include_once "../backend/messaging-functions.php";
include_once '../backend/databaseconn.php';
?>

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-196856891-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-196856891-1');
</script>

<script src="https://kit.fontawesome.com/7b2e0bb563.js" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="../javascript/profile.js"></script>


<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <link href="https://fonts.googleapis.com/css2?family=Mitr&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Mitr:wght@200&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=MuseoModerno:wght@700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@200&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@700&family=Ubuntu:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300&family=Ubuntu:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/profile.css">
    <title></title>
  </head>
  <body>
    <aside class="side-bar">

      <a href="home.php"><div class="messaging-link"></div></a>
      <a href="community.php"><i class="fa fa-users fa-2x" style="margin-left: 0.7vw; color: white;" aria-hidden="true"></i></a>
      <a href="games.php"><i class="fas fa-gamepad" style="margin-top: 1vh;margin-left: 0.5vw;font-size: 2.25em;color: white;" aria-hidden="true"></i></a>
    </aside>


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

      <div class="profile-div">

        <div class="profile-and-bio-div">

          <div class="profilepic" id="profilepic">
            <label class="label" for="file" class="file_upload_btn"><i class="fa fa-pencil-square pencil-profile fa-2x" aria-hidden="true"></i></label>
            <input class="post-file" id="file" type="file" onclick="check()" onchange="updateprofilepic()" name="post-file">
            <p class="userusername" id="userusername"></p>
          </div>


          <div class="bio-div">
            <p class="bio-text" id="bio-text"></p>
            <i onclick="updatebio()" class="fa fa-pencil-square pencil-bio fa-2x" aria-hidden="true"></i>
          </div>


        </div>

        <div class="postdisplaytitle">
            <p class="postdisplaytext">Your posts :</p>
          </div>


      </div>
      <p hidden id="sortByStatus">newest</p>
      <div id="sort-by" class="sort-by">
        <h3 style="text-align: center;">Sort by :</h3>
        <button class="sortbyitems" onclick="sortBy('newest')">Newest</button>
        <button class="sortbyitems" onclick="sortBy('oldest')">Oldest</button>
        <button class="sortbyitems" onclick="sortBy('community')">Community</button>
      </div>

      <div class="user-post-id" id="post-div">
      </div>

      </main>

  </body>

</html>
