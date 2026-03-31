<?php session_start();
include_once "../backend/databaseconn.php";

error_reporting(E_ALL);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);




if (isset($_POST['findmyposts'])) {
    if ($_SESSION['logged-in'] == true) {

      global $conn;
      $returnArr = Array();
      $sql = "SELECT community_posts.community_contents_id, community_posts.community_id, community_posts.user_id,  community_posts.date_time, community_posts.community_id, community_posts.unique_id, community_posts.numberoflikes, community_posts.numberofdislikes, community_posts.title, community_posts.post, community_posts.pic_or_vid, community_posts.filetype, users.username
      FROM community_posts
      INNER JOIN users
      ON community_posts.user_id=users.id
      WHERE community_posts.user_id=?
      ORDER BY community_posts.community_contents_id DESC
      LIMIT 20";
      $stmt = mysqli_stmt_init($conn);
      if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo "There was a problem, please try again";
      } else {
        mysqli_stmt_bind_param($stmt, "i", $_SESSION['user-id']);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
      }
      while ($row = $result->fetch_assoc()) {
        array_push($returnArr, $row);
      }

      //ENCODES ARRAY WITH INFO INTO JSON TO BE PARSED BY THE FRONTEND IN JS
      $returnArr = json_encode($returnArr);
      echo $returnArr;

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
              ORDER BY comments.community_comments_id DESC";
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
            $result = mysqli_num_rows($result);
          }

          if ($result == 0) {
            $allowed_to_like = true;
          }
          if ($result != 0) {
            $allowed_to_like = false;
          }



          //IF USER WANTS TO LIKE THE POST AND IS ALLOWED TO
          if ($like_or_dislike == "like" && $allowed_to_like == true) {

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

          if ($like_or_dislike == "dislike" && $allowed_to_like == true) {



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


        }



        if (isset($_POST['setprofilepic'])) {

          if (isset($_SESSION['logged-in']) and $_SESSION['logged-in']) {


            $sql = "SELECT * FROM users WHERE id=?";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
              echo "There was a problem, please try again";
            } else {
              mysqli_stmt_bind_param($stmt, "i", $_SESSION['user-id']);
              mysqli_stmt_execute($stmt);
              $result = mysqli_stmt_get_result($stmt);
            }
            while ($row = $result->fetch_assoc()) {
              echo $row['avatar'];
          }



          } else {
            header("Location: ../frontend/notloggedin.php");
          }

        }



        if (isset($_POST['setuserbio'])) {

          if (isset($_SESSION['logged-in']) and $_SESSION['logged-in']) {


            $sql = "SELECT * FROM users WHERE id=?";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
              echo "There was a problem, please try again";
            } else {
              mysqli_stmt_bind_param($stmt, "i", $_SESSION['user-id']);
              mysqli_stmt_execute($stmt);
              $result = mysqli_stmt_get_result($stmt);
            }
            while ($row = $result->fetch_assoc()) {
              echo $row['bio'];
          }



          } else {
            header("Location: ../frontend/notloggedin.php");
          }

        }



        if (isset($_POST['setuserusername'])) {

          if (isset($_SESSION['logged-in']) and $_SESSION['logged-in']) {


            $sql = "SELECT * FROM users WHERE id=?";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
              echo "There was a problem, please try again";
            } else {
              mysqli_stmt_bind_param($stmt, "i", $_SESSION['user-id']);
              mysqli_stmt_execute($stmt);
              $result = mysqli_stmt_get_result($stmt);
            }
            while ($row = $result->fetch_assoc()) {
              echo $row['username'];
          }



          } else {
            header("Location: ../frontend/notloggedin.php");
          }

        }

        if (isset($_POST['load_more_posts'])) {

          if (isset($_POST['newest'])) {
            $last_post_id = $_POST['last_post_id'];

            global $conn;
            $returnArr = Array();
            $sql = "SELECT community_posts.community_contents_id, community_posts.community_id, community_posts.user_id,  community_posts.date_time, community_posts.community_id, community_posts.unique_id, community_posts.numberoflikes, community_posts.numberofdislikes, community_posts.title, community_posts.post, community_posts.pic_or_vid, community_posts.filetype, users.username
            FROM community_posts
            INNER JOIN users
            ON community_posts.user_id=users.id
            WHERE community_posts.user_id=? AND community_posts.community_contents_id<?
            ORDER BY community_posts.community_contents_id DESC
            LIMIT 20";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
              echo "There was a problem, please try again";
            } else {
              mysqli_stmt_bind_param($stmt, "ii", $_SESSION['user-id'], $last_post_id);
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

          if (isset($_POST['oldest'])) {
            $last_post_id = $_POST['last_post_id'];

            global $conn;
            $returnArr = Array();
            $sql = "SELECT community_posts.community_contents_id, community_posts.community_id, community_posts.user_id,  community_posts.date_time, community_posts.community_id, community_posts.unique_id, community_posts.numberoflikes, community_posts.numberofdislikes, community_posts.title, community_posts.post, community_posts.pic_or_vid, community_posts.filetype, users.username
            FROM community_posts
            INNER JOIN users
            ON community_posts.user_id=users.id
            WHERE community_posts.user_id=? AND community_posts.community_contents_id>?
            ORDER BY community_posts.community_contents_id ASC
            LIMIT 20";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
              echo "There was a problem, please try again";
            } else {
              mysqli_stmt_bind_param($stmt, "ii", $_SESSION['user-id'], $last_post_id);
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

          if (isset($_POST['community'])) {
            $last_post_id = $_POST['last_post_id'];

            global $conn;
            $returnArr = Array();
            $sql = "SELECT community_posts.community_contents_id, community_posts.community_id, community_posts.user_id,  community_posts.date_time, community_posts.community_id, community_posts.unique_id, community_posts.numberoflikes, community_posts.numberofdislikes, community_posts.title, community_posts.post, community_posts.pic_or_vid, community_posts.filetype, users.username, communities.community_name
            FROM community_posts
            INNER JOIN users
            ON community_posts.user_id=users.id
            INNER JOIN communities
            ON community_posts.community_id=communities.community_id
            WHERE community_posts.user_id=? AND community_posts.community_contents_id<?
            ORDER BY community_posts.community_contents_id DESC LIMIT 20";

            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
              echo "There was a problem, please try again";
            } else {
              mysqli_stmt_bind_param($stmt, "ii", $_SESSION['user-id'], $last_post_id);
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

        }

        if (isset($_POST['newprofilepic'])) {
          $file_name = $_FILES['file']['name'];
          $file_size = $_FILES['file']['size'];
          $file_tmp = $_FILES['file']['tmp_name'];
          $file_type = $_FILES['file']['type'];
          move_uploaded_file($file_tmp,'../userfiles/'.$file_name);
          $fileplace = '../userfiles/'.$file_name;

          $sql = "UPDATE users SET avatar=? WHERE id=?";
          $stmt = mysqli_stmt_init($conn);
          if (!mysqli_stmt_prepare($stmt, $sql)) {
            echo "There was a problem, please try again";
          } else {
            mysqli_stmt_bind_param($stmt, "si", $fileplace, $_SESSION['user-id']);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
          }
          echo $result;
        }

if (isset($_POST['setnewbio'])) {
  $newbio = $_POST['newbio'];


  $sql = "UPDATE users SET bio=? WHERE id=?";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    echo "There was a problem, please try again";
  } else {
    mysqli_stmt_bind_param($stmt, "si", $newbio, $_SESSION['user-id']);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
  }
  echo $result;
}

//FUNCTION FINDS THE USER'S FIRST 20 POSTS, EITHER BY NEWEST, OLDEST, OR BY COMMUNITY
if (isset($_POST['sorting'])) {

  if (isset($_POST['newest'])) {
    global $conn;
    $returnArr = Array();
    $sql = "SELECT community_posts.community_contents_id, community_posts.community_id, community_posts.user_id,  community_posts.date_time, community_posts.community_id, community_posts.unique_id, community_posts.numberoflikes, community_posts.numberofdislikes, community_posts.title, community_posts.post, community_posts.pic_or_vid, community_posts.filetype, users.username
    FROM community_posts
    INNER JOIN users
    ON community_posts.user_id=users.id
    WHERE community_posts.user_id=?
    ORDER BY community_posts.community_contents_id DESC
    LIMIT 20";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
      echo "There was a problem, please try again";
    } else {
      mysqli_stmt_bind_param($stmt, "i", $_SESSION['user-id']);
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
  //IF THE USER WANTS TO SORT BY COMMUNITIES
  if (isset($_POST['community'])) {
    global $conn;
    $returnArr = Array();
    $sql = "SELECT community_posts.community_contents_id, community_posts.community_id, community_posts.user_id,  community_posts.date_time, community_posts.community_id, community_posts.unique_id, community_posts.numberoflikes, community_posts.numberofdislikes, community_posts.title, community_posts.post, community_posts.pic_or_vid, community_posts.filetype, users.username, communities.community_name
    FROM community_posts
    INNER JOIN users
    ON community_posts.user_id=users.id
    INNER JOIN communities
    ON community_posts.community_id=communities.community_id
    WHERE community_posts.user_id=?
    ORDER BY community_posts.community_contents_id DESC LIMIT 20";

    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
      echo "There was a problem, please try again";
    } else {
      mysqli_stmt_bind_param($stmt, "i", $_SESSION['user-id']);
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

  //IF THE USER WANTS TO SORT BY OLDEST
  if (isset($_POST['oldest'])) {
    global $conn;
    $returnArr = Array();
    $sql = "SELECT community_posts.community_contents_id, community_posts.community_id, community_posts.user_id,  community_posts.date_time, community_posts.community_id, community_posts.unique_id, community_posts.numberoflikes, community_posts.numberofdislikes, community_posts.title, community_posts.post, community_posts.pic_or_vid, community_posts.filetype, users.username
    FROM community_posts
    INNER JOIN users
    ON community_posts.user_id=users.id
    WHERE community_posts.user_id=?
    ORDER BY community_posts.community_contents_id ASC
    LIMIT 20";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
      echo "There was a problem, please try again";
    } else {
      mysqli_stmt_bind_param($stmt, "i", $_SESSION['user-id']);
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
}
?>
