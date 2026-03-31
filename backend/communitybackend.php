<?php
session_start();
include_once '../backend/databaseconn.php';

error_reporting(E_ALL);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// FUNCTION TO FIND THE COMMUNITY NAME BASED ON THE COMMUNITY ID
if (isset($_POST['community_find_name'])) {
  $community_id = $_POST['community_id'];
  $sql = "SELECT community_name FROM communities WHERE community_id=?";
  $stmt = mysqli_stmt_init($conn);
      if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo "There was a problem, please try again";
      } else {
        mysqli_stmt_bind_param($stmt, "i", $community_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
      }
    while ($row = $result->fetch_assoc()) {
      //.JSON ENCODES THE COMMUNITY NAME THEN SENDS IT BACK TO THE FRONTEND
      $community_name = json_encode($row['community_name']);
      echo $community_name;
    }

  }


//THIS IS A FUNCTION THAT FINDS OUT IF A USER IS ALREADY A PART OF THE COMMUNITY THEY ARE DISCOVERING/ON
if (isset($_POST['set_join_btn'])) {

    $sql = "SELECT * FROM community_members WHERE user_id=? AND community_id=?";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
      echo "There was a problem, please try again";
    } else {
      mysqli_stmt_bind_param($stmt, "ii", $_SESSION['user-id'], $_POST['community_id']);
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);
    }
    if ($result != null) {
      $numofrows = mysqli_num_rows($result);
    } else {
      $numofrows = 0;
    }
    if ($numofrows == 0) {
      //USER IS NOT A PART OF THIS COMMUNITY
      $not_a_member = json_encode("this user is not a member of the given community");
      echo $not_a_member;
    } else {
      $member = json_encode("this user is a member of the given community");
      echo $member;
    }
}



//THIS IS A FUNCTION THAT LETS A USER JOIN/LEAVE FROM A COMMUNITY
if (isset($_POST['join_leave_community'])) {
  if (isset($_SESSION['logged-in']) and $_SESSION['logged-in']) {
  $community_id = $_POST['community_id'];
  date_default_timezone_set('America/New_York');
  $jointime = date("y-m-d H:i:s");

  $sql = "SELECT * FROM community_members WHERE community_id=? AND user_id=?";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    echo "There was a problem, please try again!";
  } else {
    mysqli_stmt_bind_param($stmt, "ii", $community_id, $_SESSION['user-id']);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
  }
  if ($result != null) {

    $result = mysqli_num_rows($result);

  } else {

    $result = 0;

  }


  if ($result != 0) {
    //IF USER IS IN THE COMMUNITY THEN DELETE THE USER (BECAUSE THEY WANT TO LEAVE)
  $sql = "DELETE FROM community_members WHERE community_id=? AND user_id=?";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    echo "There was a problem, please try again!";
  } else {
    mysqli_stmt_bind_param($stmt, "ii", $community_id, $_SESSION['user-id']);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
  }
  //TELLS THE FRONTEND THAT THE USER HAS LEFT THE COMMUNITY
  $leaving_community = json_encode("You have left!");
  echo $leaving_community;

  } else {
  //ADDS THE USER AS A MEMBER OF THE COMMUNITY
  $sql = "INSERT INTO community_members (community_id, jointime, user_id) VALUES (?, ?, ?)";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    echo "There was a problem, please try again!";
  } else {
    mysqli_stmt_bind_param($stmt, "isi", $community_id, $jointime,$_SESSION['user-id']);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
  }
  //TELLS THE FRONTEND THAT THE USER HAS JOINED
  $leaving_community = json_encode("You have joined!");
  echo $leaving_community;

  }

}
}


if (isset($_POST['findposts'])) {
  $community_id = $_POST['community_id'];
  //FUNCTION THAT FINDS THE FIRST 20 POSTS OF A CERTAIN COMMUNITY
  $returnArr = Array();
  $sql = "SELECT community_posts.community_contents_id, community_posts.user_id,  community_posts.date_time, community_posts.community_id, community_posts.unique_id, community_posts.numberoflikes, community_posts.numberofdislikes, community_posts.title, community_posts.post, community_posts.pic_or_vid, community_posts.filetype, users.username
  FROM community_posts
  INNER JOIN users
  ON community_posts.user_id=users.id
  WHERE community_posts.community_id=?
  ORDER BY community_posts.community_contents_id DESC
  LIMIT 20 ";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    echo "There was a problem, please try again";
  } else {
    mysqli_stmt_bind_param($stmt, "i", $community_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
  }
  while ($row = $result->fetch_assoc()) {
    array_push($returnArr, $row);
  }


  $output_array = json_encode($returnArr);
  //SENDS ARRAY OF ARRAYS CONTAINING ALL THE POST INFORMATION
  echo $output_array;


}




//FUNCTION THAT LOADS MORE POSTS WHEN YOU REACH THE BOTTOM OF THE SCREEN
if (isset($_POST['load_more_posts'])) {
  //GETS ALL THE POST INFORMATION
  $sortBy = $_POST['sortby'];
  $last_post_id = $_POST['last_post_id'];
  $community_id = $_POST['community_id'];

//IF THE USER IS LOOKING A THE NEWEST POSTS
  if ($sortBy == "newest") {

    //FINDS THE POSTS
      $returnArr = Array();
      $sql = "SELECT community_posts.community_contents_id, community_posts.user_id,  community_posts.date_time, community_posts.community_id, community_posts.unique_id, community_posts.numberoflikes, community_posts.numberofdislikes, community_posts.title, community_posts.post, community_posts.pic_or_vid, community_posts.filetype, users.username
      FROM community_posts
      INNER JOIN users
      ON community_posts.user_id=users.id
      WHERE community_posts.community_id=? AND community_posts.community_contents_id<?
      ORDER BY community_posts.community_contents_id DESC
      LIMIT 20 ";
      $stmt = mysqli_stmt_init($conn);
      if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo "There was a problem, please try again";
      } else {
        mysqli_stmt_bind_param($stmt, "ii", $community_id, $last_post_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
      }
      while ($row = $result->fetch_assoc()) {
        array_push($returnArr, $row);
      }


      $output_array = json_encode($returnArr);
      echo $output_array;
      //SENDS ARRAY OF ARRAYS CONTAINING ALL THE POST INFORMATION

  }

//IF THE USER IS LOOKING A THE OLDEST POSTS
  if ($sortBy == "oldest") {
    //FINDS THE POSTS
      $returnArr = Array();
      $sql = "SELECT community_posts.community_contents_id, community_posts.user_id,  community_posts.date_time, community_posts.community_id, community_posts.unique_id, community_posts.numberoflikes, community_posts.numberofdislikes, community_posts.title, community_posts.post, community_posts.pic_or_vid, community_posts.filetype, users.username
      FROM community_posts
      INNER JOIN users
      ON community_posts.user_id=users.id
      WHERE community_posts.community_id=? AND community_posts.community_contents_id>?
      ORDER BY community_posts.community_contents_id ASC
      LIMIT 20 ";
      $stmt = mysqli_stmt_init($conn);
      if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo "There was a problem, please try again";
      } else {
        mysqli_stmt_bind_param($stmt, "ii", $community_id, $last_post_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
      }
      while ($row = $result->fetch_assoc()) {
        array_push($returnArr, $row);
      }

      //JSON ENCODES ALL THE POST INFORMATION
      $output_array = json_encode($returnArr);
      echo $output_array;


  }




}

//FUNCTION THAT FINDS THE 20 FIRST POSTS EITHER IN ASC ORDER OR IN DESC ORDER
if (isset($_POST['sorting'])) {

  $community_id = $_POST['community_id'];

  //FINDS THE 20 FIRST POSTS IN DESC ORDER
  if (isset($_POST['newest'])) {

    $returnArr = Array();
    $sql = "SELECT community_posts.community_contents_id, community_posts.user_id,  community_posts.date_time, community_posts.community_id, community_posts.unique_id, community_posts.numberoflikes, community_posts.numberofdislikes, community_posts.title, community_posts.post, community_posts.pic_or_vid, community_posts.filetype, users.username
    FROM community_posts
    INNER JOIN users
    ON community_posts.user_id=users.id
    WHERE community_posts.community_id=?
    ORDER BY community_posts.community_contents_id DESC
    LIMIT 20 ";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
      echo "There was a problem, please try again";
    } else {
      mysqli_stmt_bind_param($stmt, "i", $community_id);
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);
    }
    while ($row = $result->fetch_assoc()) {
      array_push($returnArr, $row);
    }

    //JSON ENCODES THE ARRAY THAT IS RETURNED
    $output_array = json_encode($returnArr);

  }

  if (isset($_POST['oldest'])) {
    //FINDS THE 20 FIRST POSTS IN ASC ORDER
    $returnArr = Array();
    $sql = "SELECT community_posts.community_contents_id, community_posts.user_id,  community_posts.date_time, community_posts.community_id, community_posts.unique_id, community_posts.numberoflikes, community_posts.numberofdislikes, community_posts.title, community_posts.post, community_posts.pic_or_vid, community_posts.filetype, users.username
    FROM community_posts
    INNER JOIN users
    ON community_posts.user_id=users.id
    WHERE community_posts.community_id=?
    ORDER BY community_posts.community_contents_id ASC
    LIMIT 20 ";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
      echo "There was a problem, please try again";
    } else {
      mysqli_stmt_bind_param($stmt, "i", $community_id);
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);
    }
    while ($row = $result->fetch_assoc()) {
      array_push($returnArr, $row);
    }

    //JSON ENCODES THE ARRAY THAT IS RETURNED
    $output_array = json_encode($returnArr);

  }


  echo $output_array;

  }

//FUNCTION THAT DISPLAYS THE COMMENTS
  if (isset($_POST['comments_appear'])) {


    $community_id = $_POST['community_id'];
    $post_id = $_POST['post_id'];

    //FINDS ALL COMMENTS BUT DISPLAYS THE OLDEST COMMENTS FIRST
    $returnArr = Array();
    $sql = "SELECT comments.community_comments_id, comments.user_id, comments.community_id, comments.post_id, comments.comment, comments.date_time, users.username FROM comments
            INNER JOIN users
            ON comments.user_id=users.id
            WHERE comments.community_id=? AND comments.post_id=?
            ORDER BY comments.community_comments_id ASC";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
      echo "There was a problem, please try again";
    } else {
      mysqli_stmt_bind_param($stmt, "ii", $community_id, $post_id);
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);
    }
    while ($row = $result->fetch_assoc()) {
      array_push($returnArr, $row);
    }

    //JSON ENCODES THE ARRAY THAT IS RETURNED
    $output_array = json_encode($returnArr);

    echo $output_array;


  }

//FUNCTION THAT SENDS A COMMENT
  if (isset($_POST['send_comment'])) {
    if (isset($_SESSION['logged-in']) and $_SESSION['logged-in']) {
    if ($_SESSION['user-id'] == null) {
      echo "You must be logged in to comment!";
    }
    $post_id = $_POST['post_id'];
    $community_id = $_POST['community_id'];
    $comment_content = $_POST['comment_content'];
    date_default_timezone_set('UTC');
   $date =   date('Y-m-d H:i:s', time());

    $sql = "INSERT INTO comments (user_id, community_id, post_id, comment, date_time) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
      echo "There was a problem, please try again";
    } else {
      mysqli_stmt_bind_param($stmt, "iiiss", $_SESSION['user-id'], $community_id, $post_id, $comment_content, $date);
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);
    }
  }
  }

//CHECKS IF USER IS LOGGED IN
if (isset($_SESSION['logged-in'])) {

  //LIKES/DISLIKES A POST
    if (isset($_POST['likedislike'])) {
      $post_id = $_POST['post_id'];
      $community_id = $_POST['community_id'];
      $like_or_dislike = $_POST['like_or_dislike'];

      //CHECKS IF USER HAS ALREADY LIKED OR DISLIKED THIS POST
      $sql = "SELECT * FROM community_likes WHERE post_id=? AND user_id=?";
      $stmt = mysqli_stmt_init($conn);
      if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo "There was a problem, please try again";
      } else {
        mysqli_stmt_bind_param($stmt, "ii", $post_id, $_SESSION['user-id']);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $result1 = mysqli_num_rows($result);
      }

      if ($result1 == 0) {
        $liked_or_disliked = "null";
      }
      if ($result1 != 0) {
        while ($row = $result->fetch_assoc()) {
          $liked_or_disliked = $row['likeordislike'];
      }
      }


      //IF USER WANTS TO LIKE THE POST AND IS ALLOWED TO
      if ($like_or_dislike == "like") {
        if ($liked_or_disliked == "null") {
          //ADDS THE LIKE IN THE LIKE TABLE
          $sql = "INSERT INTO community_likes (post_id, user_id, likeordislike) VALUES (?, ?, ?)";
          $stmt = mysqli_stmt_init($conn);
          if (!mysqli_stmt_prepare($stmt, $sql)) {
            echo "There was a problem, please try again";
          } else {
            mysqli_stmt_bind_param($stmt, "iis", $post_id, $_SESSION['user-id'], $like_or_dislike);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
          }

          echo "successfully liked post";

          //UPDATES THE POST AND ADDS 1 LIKE
          $sql = "UPDATE community_posts SET numberoflikes = numberoflikes + 1 WHERE community_contents_id=?";
          $stmt = mysqli_stmt_init($conn);
          if (!mysqli_stmt_prepare($stmt, $sql)) {
            echo "There was a problem, please try again";
          } else {
            mysqli_stmt_bind_param($stmt, "i", $post_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
          }



        }
        if ($liked_or_disliked == "like") {

          $sql = "DELETE FROM community_likes WHERE post_id=? AND user_id=? AND likeordislike=?";
          $stmt = mysqli_stmt_init($conn);
          if (!mysqli_stmt_prepare($stmt, $sql)) {
            echo "There was a problem, please try again";
          } else {
            mysqli_stmt_bind_param($stmt, "iis", $post_id, $_SESSION['user-id'], $like_or_dislike);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
          }
          echo "successfully unliked post";

          //UPDATES THE POST AND ADDS 1 LIKE
          $sql = "UPDATE community_posts SET numberoflikes = numberoflikes - 1 WHERE community_contents_id=?";
          $stmt = mysqli_stmt_init($conn);
          if (!mysqli_stmt_prepare($stmt, $sql)) {
            echo "There was a problem, please try again";
          } else {
            mysqli_stmt_bind_param($stmt, "i", $post_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
          }



        }



      }

      if ($like_or_dislike == "dislike") {

        if ($liked_or_disliked == "null") {

          //ADDS THE DISLIKE IN THE LIKE TABLE
          $sql = "INSERT INTO community_likes (post_id, user_id, likeordislike) VALUES (?, ?, ?)";
          $stmt = mysqli_stmt_init($conn);
          if (!mysqli_stmt_prepare($stmt, $sql)) {
            echo "There was a problem, please try again";
          } else {
            mysqli_stmt_bind_param($stmt, "iis", $post_id, $_SESSION['user-id'],$like_or_dislike);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
          }

          echo "successfully disliked post";

          //UPDATES THE POST AND ADDS 1 DISLIKE
          $sql = "UPDATE community_posts SET numberofdislikes = numberofdislikes + 1 WHERE community_contents_id=?";
          $stmt = mysqli_stmt_init($conn);
          if (!mysqli_stmt_prepare($stmt, $sql)) {
            echo "There was a problem, please try again";
          } else {
            mysqli_stmt_bind_param($stmt, "i", $post_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
          }

        }
        if ($liked_or_disliked == "dislike") {

            //ADDS THE DISLIKE IN THE LIKE TABLE
            $sql = "DELETE FROM community_likes WHERE post_id=? AND user_id=? AND likeordislike=?";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
              echo "There was a problem, please try again";
            } else {
              mysqli_stmt_bind_param($stmt, "iis", $post_id, $_SESSION['user-id'],$like_or_dislike);
              mysqli_stmt_execute($stmt);
              $result = mysqli_stmt_get_result($stmt);
            }

            echo "successfully undisliked post";

            //UPDATES THE POST AND ADDS 1 DISLIKE
            $sql = "UPDATE community_posts SET numberofdislikes = numberofdislikes - 1 WHERE community_contents_id=?";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
              echo "There was a problem, please try again";
            } else {
              mysqli_stmt_bind_param($stmt, "i", $post_id);
              mysqli_stmt_execute($stmt);
              $result = mysqli_stmt_get_result($stmt);
            }


          }

      }


    }

}


if (isset($_POST['sending'])) {
  if ($_SESSION['logged-in'] == true) {
    $gId = $_POST['community_id'];
    $sql = "SELECT * FROM community_members WHERE community_id=? AND user_id=?";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
      echo "There was a problem, please try again";
    } else {
      mysqli_stmt_bind_param($stmt, "ii", $gId, $_SESSION['user-id']);
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);
      $numofrows = mysqli_num_rows($result);
    }
    if ($numofrows == 0) {
      echo "You must join the community if want to post in it!";
    }
    if ($numofrows == 1) {

$uId = $_SESSION['user-id'];
      // Checking for latest post of user in this community, to check if it has been 30 mins or more since the last post :


      $sql = "SELECT date_time FROM community_posts WHERE community_id=? AND user_id=? ORDER BY community_contents_id DESC";
      $stmt = mysqli_stmt_init($conn);
      if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo "There was a problem, please try again";
      } else {
        mysqli_stmt_bind_param($stmt, "ii", $gId, $_SESSION['user-id']);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $date_time_object = mysqli_fetch_object($result);
        $post_date_time = $date_time_object->date_time;
      }

      if ($post_date_time != null) {

        // Comparing dates, Ugh, this part is so annoying :

        date_default_timezone_set('UTC');
       $date_time =   date('Y-m-d H:i:s', time());
       $date_time2 = $post_date_time;

       $date1 = strtotime($date_time);
       $date2 = strtotime($date_time2);

       $diff = abs($date2 - $date1);
       $minutes = floor($diff / 60);

        if ($minutes > 10) {
          $Cooldown = "Now you can post!";
        } else {
          echo "Sorry, you must wait 15 mins before posting again!";
          $Cooldown = "WAIT 5 MINS BEFORE POSTING";
        }


      } else {
        $Cooldown = "user hasn't posted in this community ever, yet";
      }


      if ($Cooldown == "Now you can post!" || $Cooldown == "user hasn't posted in this community ever, yet") {


        $zero = 0;
        $null = "null";

        $sql = "SELECT * FROM community_posts WHERE community_id=?";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
          echo "There was a problem, please try again1";
        } else {
          mysqli_stmt_bind_param($stmt, "i", $gId);
          mysqli_stmt_execute($stmt);
          $result = mysqli_stmt_get_result($stmt);
        }
        if ($result != null) {
          $numofrowss = mysqli_num_rows($result);
        } else {
          $numofrowss = 0;
        }

        $unique_id = $numofrowss += 1;



        if (isset($_POST['textPost'])) {
          $title = $_POST['title'];
          $post = $_POST['post'];
          date_default_timezone_set('UTC');
         $date_time =   date('Y-m-d H:i:s', time());
          $sql = "INSERT INTO community_posts (user_id, community_id, unique_id, date_time, numberoflikes, numberofdislikes, title, post, filetype) VALUES (?,?,?,?,?,?,?,?,?)";
          $stmt = mysqli_stmt_init($conn);
          if (!mysqli_stmt_prepare($stmt, $sql)) {
            echo "There was a problem, please try again2";
          } else {
            mysqli_stmt_bind_param($stmt, "iiisiisss", $_SESSION['user-id'], $gId, $unique_id, $date_time, $zero, $zero, $title, $post, $null);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            echo $result;
          }
          echo "successfully posted";
        }

        if (isset($_POST['textFilePost'])) {
          $title = $_POST['title'];
          $post = $_POST['post'];
          $file_name = $_FILES['file']['name'];
          $file_size = $_FILES['file']['size'];
          $file_tmp = $_FILES['file']['tmp_name'];
          $file_type = $_FILES['file']['type'];
          move_uploaded_file($file_tmp,'../userfiles/'.$file_name);
          $fileplace = '../userfiles/'.$file_name;
          $imagearray = array('image/jpg', 'image/jpeg', 'image/png', 'image/PNG', 'image/JPG', 'image/JPEG');
          $videoarray = array('video/mp4', 'video/mpeg4');
          if ( in_array( strtolower( $file_type ), $imagearray ) ) {
            $filetype = "textImage";
          }
          if ( in_array( strtolower( $file_type ), $videoarray ) ) {
            $filetype = "textVideo";
          }
          date_default_timezone_set('UTC');
         $date_time =   date('Y-m-d H:i:s', time());
          $sql = "INSERT INTO community_posts (user_id, community_id, unique_id, date_time, numberoflikes, numberofdislikes, title, post, pic_or_vid, filetype) VALUES (?,?,?,?,?,?,?,?,?,?)";
          $stmt = mysqli_stmt_init($conn);
          if (!mysqli_stmt_prepare($stmt, $sql)) {
            echo "There was a problem, please try again3";
          } else {
            mysqli_stmt_bind_param($stmt, "iiisiissss", $_SESSION['user-id'], $gId, $unique_id, $date_time, $zero, $zero, $title, $post, $fileplace, $filetype);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            echo $result;
          }
          echo "successfully posted";
        }

        if (isset($_POST['filePost'])) {
          $title = $_POST['title'];
          $file_name = $_FILES['file']['name'];
          $file_size = $_FILES['file']['size'];
          $file_tmp = $_FILES['file']['tmp_name'];
          $file_type = $_FILES['file']['type'];
          move_uploaded_file($file_tmp,'../userfiles/'.$file_name);
          $fileplace = '../userfiles/'.$file_name;
          $imagearray = array('image/jpg', 'image/jpeg', 'image/png', 'image/PNG', 'image/JPG', 'image/JPEG');
          $videoarray = array('video/mp4', 'video/mpeg4');
          if ( in_array( strtolower( $file_type ), $imagearray ) ) {
            $filetype = "image";
          }
          if ( in_array( strtolower( $file_type ), $videoarray ) ) {
            $filetype = "video";
          }
          date_default_timezone_set('UTC');
         $date_time =   date('Y-m-d H:i:s', time());

          $sql = "INSERT INTO community_posts (user_id, community_id, unique_id, date_time, numberoflikes, numberofdislikes, title, post, pic_or_vid, filetype) VALUES (?,?,?,?,?,?,?,?,?,?)";
          $stmt = mysqli_stmt_init($conn);
          if (!mysqli_stmt_prepare($stmt, $sql)) {
            echo "There was a problem, please try again4";
          } else {
            mysqli_stmt_bind_param($stmt, "iiisiissss", $_SESSION['user-id'], $gId, $unique_id, $date_time, $zero, $zero, $title, $null, $fileplace, $filetype);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            echo $result;
          }
          echo "successfully posted";
        }


      } else {

      }


    }
  }
}


if (isset($_POST['numofmembers'])) {
  $community_id = $_POST['community_id'];

  //FINDS THE NUMBER OF MEMBERS IN A CERTAIN COMMUNITY THEN SENDS IT BACK TO THE FRONTEND
  $sql = "SELECT * FROM community_members WHERE community_id=?";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    echo "There was a problem, please try again";
  } else {
    mysqli_stmt_bind_param($stmt, "i", $community_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $result = mysqli_num_rows($result);
  }
  echo $result;
}



//FINDS THE COMMUNITY BIO AND THEN DISPLAYS IT ON THE FRONTEND
if (isset($_POST['communitybio'])) {
  $community_id = $_POST['community_id'];
  $sql = "SELECT community_bio FROM communities WHERE community_id=?";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    echo "There was a problem, please try again";
  } else {
    mysqli_stmt_bind_param($stmt, "i", $community_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
  }
  while ($row = $result->fetch_assoc()) {
    echo $row['community_bio'];
}


}
//FUNCTION CHECKS IF THE USER HAS LIKED A POST, AND IF HE HAS, THEN IT TURNS THE LIKE BUTTON BLUE
if (isset($_POST['likebuttonsblue'])) {
  if ($_SESSION['logged-in'] == true) {

    $post_id = $_POST['post_id'];
    $sql = "SELECT * FROM community_likes WHERE post_id=? AND user_id=?";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
      echo "There was a problem, please try again";
    } else {
      mysqli_stmt_bind_param($stmt, "ii", $post_id, $_SESSION['user-id']);
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);
    }
    while ($row = $result->fetch_assoc()) {
      echo $row['likeordislike'];
  }
  } else {
    echo "dude isn't logged in";
  }
}


//SETS THE USER AVATAR
if (isset($_POST['useravatar'])) {
  if ($_SESSION['logged-in'] == true) {

    $sql = "SELECT * FROM users WHERE id=?";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
      echo "There was a problem, please try again";
    } else {
      mysqli_stmt_bind_param($stmt, "i",$_SESSION['user-id']);
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);
    }
    while ($row = $result->fetch_assoc()) {
      echo $row['avatar'];
  }


} else {
  echo "not logged in";
}



}


 ?>
