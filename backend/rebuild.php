


<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <link href="https://fonts.googleapis.com/css2?family=MuseoModerno:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <title>Devie</title>
  </head>
    <body>

<?php if (empty($_POST)): ?>

     <br><br>ARE YOU SURE YOU WANT TO COMPLETELY WIPE YOUR LOCAL DATABASE<br>
     * REBUILD ALL TABLES<br>
     * POPULATE THEM WITH TEST DATA<br><br><br>
     NOTE: A page like this is only for development use. We want to make sure we never ship this file to the internet-facing webserver!<br><br>
     <form action="rebuild.php" method="POST" onSubmit="if(!confirm('Are you sure you want to wipe the database?')){return false;}">
       <input type="submit" name="submitbutton" value="click here to nuke it">
     </form>
<?php else:
  print "<br><br>ZOMG NUKING EVERYTHING<br /><br />";

  include_once 'databaseconn.php';
  #create database stuff - no dbName in mysqli_connect()
  $user_password = password_hash('Banana1', PASSWORD_DEFAULT);
  $sql_array = array(
         "DROP DATABASE IF EXISTS $dbName;",
         "CREATE DATABASE $dbName;",
         "USE $dbName;",
         );
  foreach ($sql_array as $statement) {
    if (mysqli_query($conn, $statement)) {
      echo "SUCESS RUNNING [$statement]<br>";
    } else {
      echo "ERROR ON STATEMENT [$statement] " . mysqli_error($conn) . "<br>";
    }
  }
  #same as code above, but this time we want to specify dbName in mysqli_connect()
  $sql_array = array(
         "CREATE TABLE `users` (
          `id` int(11) AUTO_INCREMENT PRIMARY KEY,
           `username` varchar(30) NOT NULL,
           `password` text NOT NULL,
           `token` text NOT NULL,
           `avatar` varchar(300) NOT NULL,
           `email` varchar(40) NOT NULL,
           `bio` text NOT NULL,
           `firsttime` varchar(300) DEFAULT 'yes')",
         "INSERT INTO users (username, password, token, avatar, email, bio) VALUES ('John',  '$user_password', 'f3b6c9b25ebba65c2a2f1216ec77c8d115224cc6747d61524cd54ba7eca3c6f3', '../images/blankprofilepicture.png', 'JohnCena@gmail.com', 'John Cena') ",
         "INSERT INTO users (username, password, token, avatar, email, bio) VALUES ('Elon',  '$user_password', 'dbea877177212ae03f94f118cfec06ab8304557c63da942f9390814c3009b10a', '../images/blankprofilepicture.png', 'ElonMusk@gmail.com', 'Elon Musk') ",
         "INSERT INTO users (username, password, token, avatar, email, bio) VALUES ('Shawn',  '$user_password', 'cd0e4206c93b0a5d4827e4f1edce078b24a1d6c65bd14fd9e17618eeca45b628', '../images/blankprofilepicture.png', 'ShawnMendez@gmail.com', 'Mendez') ",
         "INSERT INTO users (username, password, token, avatar, email, bio) VALUES ('Robert',  '$user_password', '69372e955c6db1d56443809c7cfb97cecdea7e567ac4961e8e46b466a99e9d31', '../images/blankprofilepicture.png', 'RobertSmith@gmail.com', 'Smith') ",
         "INSERT INTO users (username, password, token, avatar, email, bio) VALUES ('Joe',  '$user_password', '3fdfb4935c489cebe46ef44dbe7c875f09806095dbcf08b75133344d41c9b10c', '../images/blankprofilepicture.png', 'JoeHill@gmail.com', 'Hill') ",
         "INSERT INTO users (username, password, token, avatar, email, bio) VALUES ('pm',  '$user_password', '08818e0febfdaf81a8099059318755d78fa5166f6a56d93c6fd66a816856fd21', '../images/blankprofilepicture.png', 'marshall.alcoholic1945@gmail.com', 'Hi! My name is Marshall! I am an alcholic') ",
        /*  I am going to be Joe and you can be Robert
            The id / autoincrement may not be necessary, but putting it there
            to get something working until we think about it more */
         "CREATE TABLE `friend_request` (
           `id` int(11) AUTO_INCREMENT PRIMARY KEY,
           `sender` int(11) NOT NULL,
           `receiver` int(11) NOT NULL
         )",
         /* Guessed at IDs based on the fact that we just created the 'users'
         table, and IDs ought to be in order */
         "INSERT INTO friend_request (sender, receiver)  VALUES (1, 5)",
         "INSERT INTO friend_request (sender, receiver)  VALUES (3, 2)",
         "INSERT INTO friend_request (sender, receiver)  VALUES (1, 4)",

         "CREATE TABLE `pwdReset` (
           pwdResetId int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
           pwdResetUsername TEXT NOT NULL,
           pwdResetSelector TEXT NOT NULL,
           pwdResetToken LONGTEXT NOT NULL,
           pwdResetExpires TEXT NOT NULL
         )",
         "CREATE TABLE `friends` (
          `id` int(10) AUTO_INCREMENT PRIMARY KEY,
          `user_one` int(11) NOT NULL,
          `user_two` int(11) NOT NULL
        )",
        "INSERT INTO friends (user_one, user_two) VALUES (5,3)",
        "INSERT INTO friends (user_one, user_two) VALUES (5,2)",
        "INSERT INTO friends (user_one, user_two) VALUES (5,4)",
        "INSERT INTO friends (user_one, user_two) VALUES (3, 5)",
        "INSERT INTO friends (user_one, user_two) VALUES (2, 5)",
        "INSERT INTO friends (user_one, user_two) VALUES (4, 5 )",

        "CREATE TABLE `groups` (
          groups_id int AUTO_INCREMENT PRIMARY KEY,
          owner_id int NOT NULL,
          group_name varchar(255) NOT NULL,
          group_avatar varchar(300) NOT NULL,
          groupbio varchar(300) NOT NULL
        )",
        "INSERT INTO groups (groups_id, owner_id, group_name, group_avatar, groupbio) VALUES (1,5,'Coding group', '../images/blankprofilepicture.png', 'It is just a group')",
        "INSERT INTO groups (groups_id, owner_id, group_name, group_avatar, groupbio) VALUES (2,3,'Homework', '../images/blankprofilepicture.png', 'It is just a group')",
        "INSERT INTO groups (groups_id, owner_id, group_name, group_avatar, groupbio) VALUES (3,4,'Food', '../images/blankprofilepicture.png', 'It is just a group')",
        "CREATE TABLE `groups_members` (
          groups_members_id int AUTO_INCREMENT PRIMARY KEY,
          groups_id int NOT NULL,
          jointime DATETIME,
          user_id int,
          FOREIGN KEY (groups_id)
              REFERENCES groups(groups_id)
              ON DELETE CASCADE
        )",
        "INSERT INTO groups_members (groups_members_id, groups_id, jointime, user_id) VALUES (1,1, '2020-04-09 12:40' ,2)",
        "INSERT INTO groups_members (groups_members_id, groups_id, jointime, user_id) VALUES (2,2, '2020-04-09 12:40' ,3)",
        "INSERT INTO groups_members (groups_members_id, groups_id, jointime, user_id) VALUES (3,3, '2020-04-07 12:40' ,4)",
        "INSERT INTO groups_members (groups_members_id, groups_id, jointime, user_id) VALUES (4,1, '2020-04-06 12:40' ,5)",
        "INSERT INTO groups_members (groups_members_id, groups_id, jointime, user_id) VALUES (5,2, '2020-04-05 12:40' ,2)",
        "INSERT INTO groups_members (groups_members_id, groups_id, jointime, user_id) VALUES (6,3, '2020-04-04 12:40' ,2)",
        "CREATE TABLE `groups_contents` (
          groups_contents_id int AUTO_INCREMENT PRIMARY KEY,
          user_id int,
          groups_id int,
          unique_id int,
          date_time DATETIME,
          message varchar(1000),
          filetype varchar(40),
          FOREIGN KEY (groups_id)
              REFERENCES groups(groups_id)
              ON DELETE cascade
        )",
        "INSERT INTO groups_contents (groups_contents_id, user_id, groups_id, unique_id, date_time, message, filetype) VALUES (1,5,1, 1, '2020-04-04 12:40' , 'lorem Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', 'text')",
        "INSERT INTO groups_contents (groups_contents_id, user_id, groups_id, unique_id, date_time, message, filetype) VALUES (2,5,1, 2, '2020-04-05 12:40','Hello, my name is Joe, I recently started a coding YAY with my uncle and we are going to be rich!', 'text')",
        "INSERT INTO groups_contents (groups_contents_id, user_id, groups_id, unique_id, date_time, message, filetype) VALUES (3,5,1, 3, '2020-04-06 12:40', 'Hello, my name is Joe, I recently started a coding YAY with my uncle and we are going to be rich!', 'text')",
        "INSERT INTO groups_contents (groups_contents_id, user_id, groups_id, unique_id, date_time, message, filetype) VALUES (4,5,1, 4, '2020-04-07 12:40','Hello, my name is Joe, I recently started a coding YAY with my uncle and we are going to be rich!', 'text')",
        "INSERT INTO groups_contents (groups_contents_id, user_id, groups_id, unique_id, date_time, message, filetype) VALUES (5,5,1, 5, '2020-04-08 12:40','Hello, my name is Joe, I recently started a coding YAY with my uncle and we are going to be rich!', 'text')",
        "INSERT INTO groups_contents (groups_contents_id, user_id, groups_id, unique_id, date_time, message, filetype) VALUES (6,5,1, 6, '2020-04-09 12:40','Hello, my name is Joe, I recently started a coding YAY with my uncle and we are going to be rich!', 'text')",
        "INSERT INTO groups_contents (groups_contents_id, user_id, groups_id, unique_id, date_time, message, filetype) VALUES (7,5,1, 7, '2020-04-10 12:40','Hello, my name is Joe, I recently started a coding YAY with my uncle and we are going to be rich!', 'text')",
        "INSERT INTO groups_contents (groups_contents_id, user_id, groups_id, unique_id, date_time, message, filetype) VALUES (8,5,1, 8, '2020-04-11 12:40','Hello, my name is Joe, I recently started a coding YAY with my uncle and we are going to be rich!', 'text')",
        "INSERT INTO groups_contents (groups_contents_id, user_id, groups_id, unique_id, date_time, message, filetype) VALUES (9,5,1, 9, '2020-04-12 12:40','Hello, my name is Joe, I recently started a coding YAY with my uncle and we are going to be rich!', 'text')",
        "INSERT INTO groups_contents (groups_contents_id, user_id, groups_id, unique_id, date_time, message, filetype) VALUES (10,4,3, 1,'2020-04-13 12:40','Hello, my name is Robert, I recently started a coding project with my nephew and we are going to be rich!', 'text')",

        "CREATE TABLE `communities` (
          community_id int AUTO_INCREMENT PRIMARY KEY,
          community_name varchar(255) NOT NULL,
          community_bio varchar(600) NOT NULL,
          community_avatar varchar(300) NOT NULL,
          community_banner varchar(300) NOT NULL,
          create_time DATETIME
        )",

        "INSERT INTO communities (community_id, community_name, community_bio, community_avatar, community_banner, create_time) VALUES (1,'Minecraft','Minecraft is a game where people can place blocks and then break them!', '../images/minecraftsqaure.png', '../images/banner.png', '2002-04-05 12:40')",
        "INSERT INTO communities (community_id, community_name, community_bio, community_avatar, community_banner, create_time) VALUES (2,'Call of Duty : WWWII','Call of Duty is bullets and boom!', '../images/blankprofilepicture.png', '../images/blankprofilepicture.png', '2002-04-05 12:40')",

        "CREATE TABLE `community_members` (
          community_members_id int AUTO_INCREMENT PRIMARY KEY,
          community_id int NOT NULL,
          jointime DATETIME,
          user_id int,
          FOREIGN KEY (community_id)
              REFERENCES communities(community_id)
              ON DELETE CASCADE
        )",

        "INSERT INTO community_members (community_id, jointime, user_id) VALUES (1, '2020-04-01 12:40' ,2)",
        "INSERT INTO community_members (community_id, jointime, user_id) VALUES (1, '2020-04-02 13:40' ,5)",
        "INSERT INTO community_members (community_id, jointime, user_id) VALUES (1, '2004-11-05 05:30' ,6)",

        "CREATE TABLE `community_posts` (
          community_contents_id int AUTO_INCREMENT PRIMARY KEY,
          user_id int,
          community_id int,
          unique_id int,
          date_time DATETIME,
          numberoflikes int,
          numberofdislikes int,
          title varchar(50),
          post varchar(3000),
          pic_or_vid varchar(1000),
          filetype varchar(40),
          FOREIGN KEY (community_id)
              REFERENCES communities(community_id)
              ON DELETE cascade
        )",

        "INSERT INTO community_posts (user_id, community_id, unique_id, date_time, numberoflikes, numberofdislikes, title, post, pic_or_vid, filetype) VALUES (5 , 1 , 1 , '2020-04-05 12:40', 90, 0, 'Better keep watching!!!', 'Hello, my name is Joe, I recently started a coding YAY with my uncle and we are going to be rich!', '../images/blankprofilepicture.png', 'null')",
        "INSERT INTO community_posts (user_id, community_id, unique_id, date_time, numberoflikes, numberofdislikes, title, post, pic_or_vid, filetype) VALUES (5 , 1 , 2 , '2020-04-05 12:40', 90, 0, 'That moment...', 'Hello, my name is Joe, I recently started a coding YAY with my uncle and we are going to be rich!', '../images/blankprofilepicture.png', 'null')",
        "INSERT INTO community_posts (user_id, community_id, unique_id, date_time, numberoflikes, numberofdislikes, title, post, pic_or_vid, filetype) VALUES (5 , 1 , 3 , '2020-04-05 12:40', 90, 0, 'LOL', 'Hello, my name is Joe, I recently started a coding YAY with my uncle and we are going to be rich!', '../images/blankprofilepicture.png', 'null')",
        "INSERT INTO community_posts (user_id, community_id, unique_id, date_time, numberoflikes, numberofdislikes, title, post, pic_or_vid, filetype) VALUES (5 , 1 , 4 , '2020-04-05 12:40', 90, 0, 'LOL', 'Hello, my name is Joe, I recently started a coding YAY with my uncle and we are going to be rich!', '../images/blankprofilepicture.png', 'null')",
        "INSERT INTO community_posts (user_id, community_id, unique_id, date_time, numberoflikes, numberofdislikes, title, post, pic_or_vid, filetype) VALUES (5 , 1 , 5 , '2020-04-05 12:40', 90, 0, 'LOL', 'Hello, my name is Joe, I recently started a coding YAY with my uncle and we are going to be rich!', '../images/blankprofilepicture.png', 'null')",
        "INSERT INTO community_posts (user_id, community_id, unique_id, date_time, numberoflikes, numberofdislikes, title, post, pic_or_vid, filetype) VALUES (5 , 1 , 6 , '2020-04-05 12:40', 90, 0, 'LOL', 'Hello, my name is Joe, I recently started a coding YAY with my uncle and we are going to be rich!', '../images/blankprofilepicture.png', 'null')",
        "INSERT INTO community_posts (user_id, community_id, unique_id, date_time, numberoflikes, numberofdislikes, title, post, pic_or_vid, filetype) VALUES (5 , 1 , 7 , '2020-04-05 12:40', 90, 0, 'LOL', 'Hello, my name is Joe, I recently started a coding YAY with my uncle and we are going to be rich!', '../images/blankprofilepicture.png', 'null')",
        "INSERT INTO community_posts (user_id, community_id, unique_id, date_time, numberoflikes, numberofdislikes, title, post, pic_or_vid, filetype) VALUES (5 , 1 , 8 , '2020-04-05 12:40', 90, 0, 'LOL', 'Hello, my name is Joe, I recently started a coding YAY with my uncle and we are going to be rich!', '../images/blankprofilepicture.png', 'null')",
        "INSERT INTO community_posts (user_id, community_id, unique_id, date_time, numberoflikes, numberofdislikes, title, post, pic_or_vid, filetype) VALUES (5 , 1 , 9 , '2020-04-05 12:40', 90, 0, 'LOL', 'Hello, my name is Joe, I recently started a coding YAY with my uncle and we are going to be rich!', '../images/blankprofilepicture.png', 'null')",
        "INSERT INTO community_posts (user_id, community_id, unique_id, date_time, numberoflikes, numberofdislikes, title, post, pic_or_vid, filetype) VALUES (5 , 1 , 10 , '2020-04-05 12:40', 90, 0, 'LOL', 'Hello, my name is Joe, I recently started a coding YAY with my uncle and we are going to be rich!', '../images/blankprofilepicture.png', 'null')",
        "INSERT INTO community_posts (user_id, community_id, unique_id, date_time, numberoflikes, numberofdislikes, title, post, pic_or_vid, filetype) VALUES (5 , 1 , 11 , '2020-04-05 12:40', 90, 0, 'LOL', 'Hello, my name is Joe, I recently started a coding YAY with my uncle and we are going to be rich!', '../images/blankprofilepicture.png', 'null')",
        "INSERT INTO community_posts (user_id, community_id, unique_id, date_time, numberoflikes, numberofdislikes, title, post, pic_or_vid, filetype) VALUES (5 , 1 , 12 , '2020-04-05 12:40', 90, 0, 'LOL', 'Hello, my name is Joe, I recently started a coding YAY with my uncle and we are going to be rich!', '../images/blankprofilepicture.png', 'null')",
        "INSERT INTO community_posts (user_id, community_id, unique_id, date_time, numberoflikes, numberofdislikes, title, post, pic_or_vid, filetype) VALUES (5 , 1 , 13 , '2020-04-05 12:40', 90, 0, 'LOL', 'Hello, my name is Joe, I recently started a coding YAY with my uncle and we are going to be rich!', '../images/blankprofilepicture.png', 'null')",
        "INSERT INTO community_posts (user_id, community_id, unique_id, date_time, numberoflikes, numberofdislikes, title, post, pic_or_vid, filetype) VALUES (5 , 1 , 14 , '2020-04-05 12:40', 90, 0, 'LOL', 'Hello, my name is Joe, I recently started a coding YAY with my uncle and we are going to be rich!', '../images/blankprofilepicture.png', 'null')",
        "INSERT INTO community_posts (user_id, community_id, unique_id, date_time, numberoflikes, numberofdislikes, title, post, pic_or_vid, filetype) VALUES (5 , 1 , 15 , '2020-04-05 12:40', 90, 0, 'LOL', 'Hello, my name is Joe, I recently started a coding YAY with my uncle and we are going to be rich!', '../images/blankprofilepicture.png', 'null')",
        "INSERT INTO community_posts (user_id, community_id, unique_id, date_time, numberoflikes, numberofdislikes, title, post, pic_or_vid, filetype) VALUES (5 , 1 , 16 , '2020-04-05 12:40', 90, 0, 'LOL', 'Hello, my name is Joe, I recently started a coding YAY with my uncle and we are going to be rich!', '../images/blankprofilepicture.png', 'null')",
        "INSERT INTO community_posts (user_id, community_id, unique_id, date_time, numberoflikes, numberofdislikes, title, post, pic_or_vid, filetype) VALUES (5 , 1 , 17 , '2020-04-05 12:40', 90, 0, 'LOL', 'Hello, my name is Joe, I recently started a coding YAY with my uncle and we are going to be rich!', '../images/blankprofilepicture.png', 'null')",
        "INSERT INTO community_posts (user_id, community_id, unique_id, date_time, numberoflikes, numberofdislikes, title, post, pic_or_vid, filetype) VALUES (5 , 1 , 18 , '2020-04-05 12:40', 90, 0, 'LOL', 'Hello, my name is Joe, I recently started a coding YAY with my uncle and we are going to be rich!', '../images/blankprofilepicture.png', 'null')",
        "INSERT INTO community_posts (user_id, community_id, unique_id, date_time, numberoflikes, numberofdislikes, title, post, pic_or_vid, filetype) VALUES (5 , 1 , 19 , '2020-04-05 12:40', 90, 0, 'LOL', 'Hello, my name is Joe, I recently started a coding YAY with my uncle and we are going to be rich!', '../images/blankprofilepicture.png', 'null')",
        "INSERT INTO community_posts (user_id, community_id, unique_id, date_time, numberoflikes, numberofdislikes, title, post, pic_or_vid, filetype) VALUES (5 , 1 , 20 , '2020-04-05 12:40', 90, 0, 'LOL', 'Hello, my name is Joe, I recently started a coding YAY with my uncle and we are going to be rich!', '../images/blankprofilepicture.png', 'null')",
        "INSERT INTO community_posts (user_id, community_id, unique_id, date_time, numberoflikes, numberofdislikes, title, post, pic_or_vid, filetype) VALUES (5 , 1 , 21 , '2020-04-05 12:40', 90, 0, 'LOertrewL', 'Hello, my name is Joe, I recently started a coding YAY with my uncle and we are going to be rich!', '../images/blankprofilepicture.png', 'null')",
        "INSERT INTO community_posts (user_id, community_id, unique_id, date_time, numberoflikes, numberofdislikes, title, post, pic_or_vid, filetype) VALUES (5 , 1 , 22 , '2020-04-05 12:40', 90, 0, 'LOertytrewL', 'Hello, my name is Joe, I recently started a coding YAY with my uncle and we are going to be rich!', '../images/blankprofilepicture.png', 'null')",
        "INSERT INTO community_posts (user_id, community_id, unique_id, date_time, numberoflikes, numberofdislikes, title, post, pic_or_vid, filetype) VALUES (5 , 1 , 23 , '2020-04-05 12:40', 90, 0, 'LOrtytreL', 'Hello, my name is Joe, I recently started a coding YAY with my uncle and we are going to be rich!', '../images/blankprofilepicture.png', 'null')",
        "INSERT INTO community_posts (user_id, community_id, unique_id, date_time, numberoflikes, numberofdislikes, title, post, pic_or_vid, filetype) VALUES (5 , 1 , 24 , '2020-04-05 12:40', 90, 0, 'LOrtyuL', 'Hello, my name is Joe, I recently started a coding YAY with my uncle and we are going to be rich!', '../images/blankprofilepicture.png', 'null')",
        "INSERT INTO community_posts (user_id, community_id, unique_id, date_time, numberoflikes, numberofdislikes, title, post, pic_or_vid, filetype) VALUES (5 , 1 , 25 , '2020-04-05 12:40', 90, 0, 'LertyOL', 'Hello, my name is Joe, I recently started a coding YAY with my uncle and we are going to be rich!', '../images/blankprofilepicture.png', 'null')",
        "INSERT INTO community_posts (user_id, community_id, unique_id, date_time, numberoflikes, numberofdislikes, title, post, pic_or_vid, filetype) VALUES (5 , 1 , 26 , '2020-04-05 12:40', 90, 0, 'LOrtyL', 'Hello, my name is Joe, I recently started a coding YAY with my uncle and we are going to be rich!', '../images/blankprofilepicture.png', 'null')",
        "INSERT INTO community_posts (user_id, community_id, unique_id, date_time, numberoflikes, numberofdislikes, title, post, pic_or_vid, filetype) VALUES (5 , 1 , 27 , '2020-04-05 12:40', 90, 0, 'LOertyuiL', 'Hello, my name is Joe, I recently started a coding YAY with my uncle and we are going to be rich!', '../images/blankprofilepicture.png', 'null')",
        "INSERT INTO community_posts (user_id, community_id, unique_id, date_time, numberoflikes, numberofdislikes, title, post, pic_or_vid, filetype) VALUES (5 , 1 , 28 , '2020-04-05 12:40', 90, 0, 'LOL', 'Hello, my name is Joe, I recently started a coding YAY with my uncle and we are going to be rich!', '../images/blankprofilepicture.png', 'null')",
        "INSERT INTO community_posts (user_id, community_id, unique_id, date_time, numberoflikes, numberofdislikes, title, post, pic_or_vid, filetype) VALUES (5 , 1 , 29 , '2020-04-05 12:40', 90, 0, 'LOL', 'Hello, my name is Joe, I recently started a coding YAY with my uncle and we are going to be rich!', '../images/blankprofilepicture.png', 'null')",
        "INSERT INTO community_posts (user_id, community_id, unique_id, date_time, numberoflikes, numberofdislikes, title, post, pic_or_vid, filetype) VALUES (5 , 1 , 30 , '2020-04-05 12:40', 90, 0, 'LOL', 'Hello, my name is Joe, I recently started a coding YAY with my uncle and we are going to be rich!', '../images/blankprofilepicture.png', 'null')",
        "INSERT INTO community_posts (user_id, community_id, unique_id, date_time, numberoflikes, numberofdislikes, title, post, pic_or_vid, filetype) VALUES (5 , 1 , 31 , '2020-04-05 12:40', 90, 0, 'LOL', 'Hello, my name is Joe, I recently started a coding YAY with my uncle and we are going to be rich!', '../images/blankprofilepicture.png', 'null')",
        "INSERT INTO community_posts (user_id, community_id, unique_id, date_time, numberoflikes, numberofdislikes, title, post, pic_or_vid, filetype) VALUES (5 , 1 , 32 , '2020-04-05 12:40', 90, 0, 'LOL', 'Hello, my name is Joe, I recently started a coding YAY with my uncle and we are going to be rich!', '../images/blankprofilepicture.png', 'null')",
        "INSERT INTO community_posts (user_id, community_id, unique_id, date_time, numberoflikes, numberofdislikes, title, post, pic_or_vid, filetype) VALUES (5 , 1 , 33 , '2020-04-05 12:40', 90, 0, 'LOL', 'Hello, my name is Joe, I recently started a coding YAY with my uncle and we are going to be rich!', '../images/blankprofilepicture.png', 'null')",
        "INSERT INTO community_posts (user_id, community_id, unique_id, date_time, numberoflikes, numberofdislikes, title, post, pic_or_vid, filetype) VALUES (5 , 1 , 34 , '2020-04-05 12:40', 90, 0, 'LOL', 'Hello, my name is Joe, I recently started a coding YAY with my uncle and we are going to be rich!', '../images/blankprofilepicture.png', 'null')",
        "INSERT INTO community_posts (user_id, community_id, unique_id, date_time, numberoflikes, numberofdislikes, title, post, pic_or_vid, filetype) VALUES (5 , 1 , 35 , '2020-04-05 12:40', 90, 0, 'LOL', 'Hello, my name is Joe, I recently started a coding YAY with my uncle and we are going to be rich!', '../images/blankprofilepicture.png', 'null')",
        "INSERT INTO community_posts (user_id, community_id, unique_id, date_time, numberoflikes, numberofdislikes, title, post, pic_or_vid, filetype) VALUES (5 , 1 , 36 , '2020-04-05 12:40', 90, 0, 'LOL', 'Hello, my name is Joe, I recently started a coding YAY with my uncle and we are going to be rich!', '../images/blankprofilepicture.png', 'null')",

      "CREATE TABLE `community_likes` (
        id int AUTO_INCREMENT PRIMARY KEY,
        post_id int,
        user_id int,
        likeordislike varchar(400)
      )",

      "CREATE TABLE `games` (
        id int AUTO_INCREMENT PRIMARY KEY,
        name varchar(255),
        location varchar(2048),
        cover varchar(2048),
        description text,
        tags text,
        credits text,
        creator text,
        timesclicked int,
        controls text

      )",
      "INSERT INTO `games` (name, location, cover, description, tags, credits, creator, timesclicked, controls) VALUES ('The Blue Alien', 'https://www.addictinggames.com/embed/html5-games/24605', '../images/blue_alien.png', 'Simple Platformer', 'video platformer action', 'Stephen352, his twitter is @urmom2002', 'john', 4,'click to attack')",
      "INSERT INTO `games` (name, location, cover, description, tags, credits, creator, timesclicked, controls) VALUES ('The Red Alien', 'https://www.addictinggames.com/embed/html5-games/24605', '../images/blue_alien.png', 'Simple Platformer', 'video platformer action', 'Stephen352, his twitter is @urmom2002', 'john', 4, 'spacebar to jump')",
      "INSERT INTO `games` (name, location, cover, description, tags, credits, creator, timesclicked, controls) VALUES ('The Magenta Alien', 'https://www.addictinggames.com/embed/html5-games/24605', '../images/blue_alien.png', 'Simple Platformer', 'video platformer action', 'Stephen352, his twitter is @urmom2002', 'john', 4, 'arrow keys')",
      "INSERT INTO `games` (name, location, cover, description, tags, credits, creator, timesclicked, controls) VALUES ('Not a game', 'https://www.youtube.com/embed/dQw4w9WgXcQ', '../images/no.png', 'Do you know Rick?', 'video platformer action', 'Stephen352, his twitter is @urmom2002', 'john', 4, 'a for jump')",
      "INSERT INTO `games` (name, location, cover, description, tags, credits, creator, timesclicked, controls) VALUES ('Definitely not a rickroll', 'https://www.youtube.com/embed/dQw4w9WgXcQ', '../images/no.png', 'Do you know Rick?', 'video platformer action', 'Stephen352, his twitter is @urmom2002', 'john', 4, 'w for forward')",

      "CREATE TABLE `comments` (
        community_comments_id int AUTO_INCREMENT PRIMARY KEY,
        user_id int,
        community_id int,
        post_id int,
        comment varchar(2000),
        date_time DATETIME,
        FOREIGN KEY (community_id)
            REFERENCES communities(community_id)
            ON DELETE cascade
      )",

      "INSERT INTO comments (user_id, community_id, post_id, comment, date_time) VALUES (5, 1, 1, 'Lol I agree!', '2020-01-01 12:45')",

      "CREATE TABLE `createcommunities` (
        community_search_id int AUTO_INCREMENT PRIMARY KEY,
        search varchar(2000),
        user_id int
      )"
    );




  foreach ($sql_array as $statement) {
    if (mysqli_query($conn, $statement)) {
      echo "SUCESS RUNNING [$statement]<br>";
    } else {
      echo "ERROR ON STATEMENT [$statement] " . mysqli_error($conn) . "<br>";
    }
  }
  endif;
?>
    </body>
</html>
