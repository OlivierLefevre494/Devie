<?php session_start();

include_once '../backend/databaseconn.php';


error_reporting(E_ALL);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);


if (isset($_POST['checkiffirsttimeuser'])) {

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
    echo $row['firsttime'];
}

}


if (isset($_POST['finishedtutorial'])) {

  $sql = "UPDATE users SET firsttime='no' WHERE id=?";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    echo "There was a problem, please try again";
  } else {
    mysqli_stmt_bind_param($stmt, "i", $_SESSION['user-id']);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
  }


}
