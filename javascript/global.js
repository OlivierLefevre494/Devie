//MAKES THE USER LOGOUT
function logout() {
    var fd = new FormData();
    fd.append("logout", true);
    var xhr = new XMLHttpRequest();
    var fullurl = '../backend/Login-backend.php';
    xhr.open('POST', fullurl, true);
    xhr.onload = function() {
      if (this.status == 200) {
        window.location.href = "../frontend/login.php";
      };
  };
  xhr.send(fd);
}