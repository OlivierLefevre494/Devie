<?php session_start();
include_once "../backend/databaseconn.php";

error_reporting(E_ALL);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if (isset($_POST['playgame'])) {
  $game_id = $_POST['game_id'];


  global $conn;
  $returnArr = Array();
  $sql = "SELECT * FROM games WHERE id=?";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    echo "There was a problem, please try again";
  } else {
    mysqli_stmt_bind_param($stmt, "i", $game_id);
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


if (isset($_POST['sidegames'])) {
  $game_id = $_POST['game_id'];



  $sql = "SELECT * FROM games WHERE id=?";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    echo "There was a problem, please try again";
  } else {
    mysqli_stmt_bind_param($stmt, "i", $game_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
  }
  while ($row = $result->fetch_assoc()) {
    $tags = $row['tags'];
}


global $conn;
$returnArr = Array();
$sql = "SELECT * FROM games WHERE tags LIKE concat('%', ?, '%') AND id!=? ORDER BY id ASC LIMIT 12";
$stmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt, $sql)) {
  echo "There was a problem, please try again";
} else {
  mysqli_stmt_bind_param($stmt, "si", $tags, $game_id);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
}
$resultcheck = mysqli_num_rows($result);
if ($resultcheck != 0) {
  while ($row = $result->fetch_assoc()) {
    array_push($returnArr, $row);
  }
  //ENCODES ARRAY WITH INFO INTO JSON TO BE PARSED BY THE FRONTEND IN JS
  $returnArr = json_encode($returnArr);
} else {
  global $conn;
  $returnArr = Array();
  $sql = "SELECT * FROM games WHERE id!=? ORDER BY id ASC LIMIT 12";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    echo "There was a problem, please try again";
  } else {
    mysqli_stmt_bind_param($stmt, "i", $game_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    while ($row = $result->fetch_assoc()) {
      array_push($returnArr, $row);
    }
    //ENCODES ARRAY WITH INFO INTO JSON TO BE PARSED BY THE FRONTEND IN JS
    $returnArr = json_encode($returnArr);
  }
}

echo $returnArr;


}

if (isset($_POST['recommendmoregames'])) {
  $game_id = $_POST['game_id'];
  $lastgameid = $_POST['last_game_id'];

  $sql = "SELECT * FROM games WHERE id=?";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    echo "There was a problem, please try again";
  } else {
    mysqli_stmt_bind_param($stmt, "i", $game_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
  }
  while ($row = $result->fetch_assoc()) {
    $tags = $row['tags'];
}


global $conn;
$returnArr = Array();
$sql = "SELECT * FROM games WHERE tags LIKE concat('%', ?, '%') AND id!=? AND id>? ORDER BY id ASC LIMIT 12";
$stmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt, $sql)) {
  echo "There was a problem, please try again";
} else {
  mysqli_stmt_bind_param($stmt, "sii", $tags, $game_id, $lastgameid);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
}
while ($row = $result->fetch_assoc()) {
  array_push($returnArr, $row);
}

$resultcheck = mysqli_num_rows($result);
if ($resultcheck != 0) {
  //ENCODES ARRAY WITH INFO INTO JSON TO BE PARSED BY THE FRONTEND IN JS
  $returnArr = json_encode($returnArr);
} else {
  global $conn;
  $returnArr = Array();
  $sql = "SELECT * FROM games WHERE id!=? AND id>? ORDER BY id ASC LIMIT 12";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    echo "There was a problem, please try again";
  } else {
    mysqli_stmt_bind_param($stmt, "ii", $game_id, $lastgameid);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    while ($row = $result->fetch_assoc()) {
      array_push($returnArr, $row);
    }
    //ENCODES ARRAY WITH INFO INTO JSON TO BE PARSED BY THE FRONTEND IN JS
    $returnArr = json_encode($returnArr);
  }
}

echo $returnArr;

}


 ?>
