<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-196856891-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-196856891-1');
</script>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <script src="../javascript/friends.js"></script>
        <link rel="stylesheet" href="../css/friends.css" />
        <script src="../javascript/ws.js"></script>
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        <script src="https://kit.fontawesome.com/7b2e0bb563.js" crossorigin="anonymous"></script>
        <title>Devie - Chat</title>
    </head>

    <body>
        <!-- Header -->
        <header>
            <h1>DEVIE</h1>
            <div class="dropdown">
                <div class="avatar"></div>
                <div class="avatar-dropdown">
                    <p onclick="window.location.href='profile.php';">Profile</p>
                    <p onclick="window.location.href='friends.php';">Friends</p>
                    <p onclick="logout();">Logout</p>
                </div>
            </div>
        </header>

        <!-- Side Bar -->
        <aside id="sidebar">
            <nav>
                <ul>
                    <li class="messaging_icon" onclick="window.location.href='home.php';"></li>
                    <li onclick="window.location.href='community.php';" class="fa fa-users fa-2x"></li>
                    <li onclick="window.location.href='games.php';" class="fa fa-gamepad fa-2x"></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Section -->
        <section id="friends">
            <div id="tab_bar">
                <ul id="tabs">
                    <li onclick="show('#all')">Friends</li>
                    <li onclick="show('#inc')">Incoming Friend Requests</li>
                    <li onclick="show('#pending')">Pending Friend Requests</li>
                </ul>
            </div>

            <div id="all" class="tab_content">
                <input type="text" id="add_friend_bar" /><button onclick="add_friend()">Add Friend</button>   
            </div>
            <div id="inc" class="tab_content"></div>
            <div id="pending" class="tab_content"></div>
        </section>
    </body>
</html>
