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
        <link rel="stylesheet" href="../css/chat.css" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        <script src="https://kit.fontawesome.com/7b2e0bb563.js" crossorigin="anonymous"></script>
        <script src="../javascript/global.js"></script>
        <script src="../javascript/chat.js"></script>
        <script src="../javascript/ws.js"></script>
        <title>Devie - Chat</title>
    </head>

    <body>
        <!-- Header -->
        <header id="header">
            <h1>DEVIE</h1>
            <div class="dropdown" id="dropdown">
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
                    <li class="messaging_icon" id="messaging_link"></li>
                    <li onclick="window.location.href='community.php';" id="community_link" class="fa fa-users fa-2x"></li>
                    <li onclick="window.location.href='games.php';" id="gaming_link" class="fa fa-gamepad fa-2x"></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Section -->
        <section id="messages">
            <aside id="grp_menu">
                <h1>Chat</h1>
                <button onclick="create_group()"><img src="/images/pencil-square.svg" class="svg" alt="pencil icon" /></button>
                <ul id="grp_list">
                    <li onclick="window.location.href='friends.php';" id="friends_link"><img class="svg" src="/images/person.svg" alt="Person Icon" />&nbsp;Friends</li>
                </ul>
            </aside>
            <section id="grp_messages"></section>
            <section id="input">
                <input type="text" placeholder="Message..." name="message" id="message_bar" />
                <button id="submit-button" onclick="send();"><img src="/images/check.svg" alt="Check mark" /></button>
            </section>
        </section>
    </body>
</html>
