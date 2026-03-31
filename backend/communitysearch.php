<?php session_start();

include_once '../backend/databaseconn.php';
include_once "../backend/messaging-functions.php";


error_reporting(E_ALL);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
//FUNCTION THAT FINDS COMMUNITY NAME, AVATAR AND BANNER IF COMMUNITY EXISTS
function findcommunities($search) {
  global $conn;
  $returnArr = Array();
  $sql = "SELECT * FROM communities WHERE community_name=?";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    echo "There was a problem, please try again";
  } else {
    mysqli_stmt_bind_param($stmt, "s", $search);
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

//FUNCTION SEARCHES FOR COMMUNTIES AND SENDS THE ARRAY WITH INFO TO THE FRONTEND USING A FUNCTION
if (isset($_POST['findcommunities'])) {

  $search = $_POST['search'];
  $sql = "SELECT * FROM communities WHERE community_name=?";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    echo "There was a problem, please try again";
  } else {
    mysqli_stmt_bind_param($stmt, "s", $search);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $result = mysqli_num_rows($result);
  }
  //IF THE QUERY RETURNED NO RESULTS
  if ($result == null) {
    echo "no communities with that name";
  }
  //IF THE QUERY RETURNED 1 RESULT
  if ($result == 1) {
    findcommunities($search);
  }
}

//FUNCTION THAT FINDS COMMUNITIES FOR THE USER TO BROWSE
if (isset($_POST['browse_communities'])) {

    global $conn;
    $returnArr = Array();
    $sql = "SELECT * FROM communities ORDER BY community_id ASC LIMIT 30";
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


if (isset($_POST['createcommunity'])) {
  if (isset($_SESSION['logged-in']) and $_SESSION['logged-in']) {

    $search = $_POST['search'];

    $sql = "INSERT INTO createcommunities (search, user_id) VALUES (?, ?)";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
      echo "There was a problem, please try again";
    } else {
      mysqli_stmt_bind_param($stmt, "si", $search, $_SESSION['user-id']);
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);
    }



  } else {
    echo "not logged in";
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


if (isset($_POST['browsemorecommunities'])) {
  $lastcommunityid = $_POST['lastcommunityid'];
  global $conn;
  $returnArr = Array();
  $sql = "SELECT * FROM communities WHERE community_id>? ORDER BY community_id ASC LIMIT 30";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    echo "There was a problem, please try again";
  } else {
    mysqli_stmt_bind_param($stmt, "i", $lastcommunityid);
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
