<?php session_start();
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
<script type="text/javascript" src="../javascript/communitydisplay.js"></script>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Community display</title>
    <link href="https://fonts.googleapis.com/css2?family=Mitr&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Mitr:wght@200&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=MuseoModerno:wght@700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@200&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@700&family=Ubuntu:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300&family=Ubuntu:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/communitydisplay.css">
  </head>
  <body>



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


    <aside class="side-bar">
      <a href="home.php"><div class="messaging-link"></div></a>
      <a href="community.php"><i class="fa fa-users fa-2x" style="margin-left: 0.7vw; color: white;" aria-hidden="true"></i></a>
      <a href="games.php"><i class="fas fa-gamepad" style="margin-top: 1vh;margin-left: 0.5vw;font-size: 2.25em;color: white;" aria-hidden="true"></i></a>
    </aside>


    <main id="mainpage" class="mainpage" onscroll="onscrollfunction()">
    <div class="wrapper">
      <div class="bannerdiv">
        <img class="banner" src="<?php echo $_GET['id2']; ?>" alt="banner image">
      </div>
      <div class="community-info">
        <img class="community-avatar" height="50px" width="50px" src="<?php echo $_GET['avatar'] ?>" alt="">
        <p hidden id="community_id"><?php echo $_GET['id']; ?></p>
        <h1 class="community-title" id="community-title"></h1>
         <button class="join" id="join_leave_btn" onclick="joinCommunity()"></button>
         <div class="community-information">
           <p class="number"id="members_number">1000</p>
           <p class="members">Members</p>
           <p class="bio" id="community_bio"></p>
         </div>
      </div>
      <p hidden id="sortByStatus">newest</p>
      <div class="post-community-title">
        <h1 class="post-form-title">Post in this community:</h1>
        <div class="white-form-div">
          <div class="post-form">
            <input class="post-title" id="title" type="text" name="post-title" placeholder="Title">
            <div class="post-content-display">
              <textarea class="post-content" id="post" type="text" name="post-content" placeholder="Create a post"></textarea>
              <label class="label" for="file" class="file_upload_btn"><i class="fa fa-paperclip" aria-hidden="true"></i></label>
            </div>
            <input class="post-file" id="file" type="file" onclick="check()" name="post-file">
            <input class="post-button" type="button" name="Post" onclick="postIncoming()" value="Post">
          </div>
        </div>
      </div>
      <div id="sort-by" class="sort-by">
        <h3 style="text-align: center; margin-top: 5px;">Sort by :</h3>
        <button class="sortbyitems" onclick="sortBy('newest')">Newest</button>
        <button class="sortbyitems" onclick="sortBy('oldest')">Oldest</button>
      </div>
      <div id="posts">
        <a id="return-to-top" onclick="backToTop()"><i class="icon-arrow-up " id="back_to_top_btn"></i></a>
        <button type="button" name="button" class="load_new_posts" onclick="loadNewPosts()" id="load_btn">Load new posts</button>
</div>
</div>

    </main>
  </body>
</html>
