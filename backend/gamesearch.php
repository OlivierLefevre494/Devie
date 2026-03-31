<?php session_start();
include_once "../backend/databaseconn.php";

error_reporting(E_ALL);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

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

//FUNCTION THAT FINDS MORE COMMUNITIES FOR THE USER TO BROWSE
if (isset($_POST['findmoregames'])) {

  $lastgameid = $_POST['lastgameid'];

    global $conn;
    $returnArr = Array();
    $sql = "SELECT * FROM games WHERE id>? ORDER BY id ASC LIMIT 30";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
      echo "There was a problem, please try again";
    } else {
      mysqli_stmt_bind_param($stmt, "i", $lastgameid);
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

//FUNCTION THAT FINDS COMMUNITIES FOR THE USER TO BROWSE
if (isset($_POST['browsegames'])) {

    global $conn;
    $returnArr = Array();
    $sql = "SELECT * FROM games ORDER BY id ASC LIMIT 30";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
      echo "There was a problem, please try again";
    } else {
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

if (isset($_POST['clickedgame'])) {

  $game_id = $_POST['game_id'];

  $sql = "UPDATE games SET timesclicked = timesclicked + 1 WHERE id=?";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    echo "There was a problem, please try again";
  } else {
    mysqli_stmt_bind_param($stmt, "i", $game_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
  }

}

//FUNCTION SEARCHED FOR A GAME
if (isset($_POST['gamesearch'])) {
  $text_search = $_POST['text_search'];
  $creator_search = $_POST['creator_search'];
  $tagarray = json_decode($_POST['tagarray']);
  $sql = "SELECT * FROM games WHERE";
    //IF THE USER HAS SEARCHED FOR A CREATOR
    if ($text_search == "") {

    }
    //IF THE USER HAS SEARCHED FOR A CREATOR
    if ($text_search != "") {
      $text_search = " name LIKE concat('%','$text_search','%')";
      $sql = $sql . $text_search;
    }

    //IF THE USER HAS SEARCHED FOR A CREATOR
    if ($creator_search != "") {
      if ($text_search != "") {
        //IF THE USER HAS ALSO SEARCHED FOR A TEXT
        $creator_search = " AND creator= '$creator_search'";
      } else {
        //IF THE USER HASN'T ALSO SEARCHED FOR A TEXT
        $creator_search = " creator= '$creator_search'";
      }
      $sql = $sql . $creator_search;
    }

    if ($tagarray != []) {
      $tags = "";
      if ($text_search != "" || $creator_search != "") {
        $i = 0;
        foreach ($tagarray as $tag) {
          if ($i == 0) {
            $tags = $tags . " AND tags LIKE \"%$tag%\"";
            $i += 1;
          } else {
            $tags = $tags . " OR tags LIKE \"%$tag%\"";
            $i += 1;
          }

        }
      } else {
        $i = 0;
        foreach ($tagarray as $tag) {
          if ($i == 0) {
            $tags = $tags . " tags LIKE \"%$tag%\"";
            $i += 1;
          } else {
            $tags = $tags . " OR tags LIKE \"%$tag%\"";
            $i += 1;
          }
        }

      }
  	$sql = $sql . $tags;
      }
      global $conn;
      $returnArr = Array();
      $stmt = mysqli_stmt_init($conn);
      if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo "There was a problem, please try again";
      } else {
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

 ?>
