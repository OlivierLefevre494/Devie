// Check if user logged-in
if (getCookie("token")==="") {
  // If user not logged in, redirect
  window.location.href= "login.php";
}

// Web Socket Server URL
var socket_url = "ws://localhost:8081";
var socket;

// Logged In
var logged_in = false;

// Selected Chat
var current_chat = 0;

// Runs after page loaded
window.onload = function() {
  minitutorial();
// Connect to ws server
connect();

// Get avatar
interval = setInterval(function() {
  if (logged_in) {
    socket.send('{"action":"avatar"}');
    socket.send('{"action":"friends"}');
    clearInterval(interval);
  }
}, 1000);

// Enter to send
document.querySelector("#message_bar").addEventListener("keyup", function(event) {
  if (event.key === "Enter") {
    event.preventDefault();
    document.querySelector("#submit-button").click();
  }
});
}

function connect() {
// Connect to ws server
socket = new WebSocket(socket_url);

// Connection Estabished
socket.onopen = function (event) {
  // Login
  socket.send(JSON.stringify({
    action: "login",
    token: getCookie("token")
  }));
};

// Message Received
socket.onmessage = function (event) {
  // Logged in
  if (event.data == "logged_in") {
    logged_in = true;
    return;
  }

  //  Parse JSON
  data = JSON.parse(event.data);

  // Do required action
  switch (data.action) {
    // Group
    case "grp":
      // Create HTML Elements
      grp_block = document.createElement("div");
      grp_block.className = "grp_block";
      grp_block.dataset.groupId = data.id;
      grp_block.style.display = "none";
      list_item = document.createElement("li");
      list_item.setAttribute("onclick", "open_chat("+data.id+", this);");
      list_item.dataset.groupId = data.id;
      grp_avatar = new Image();
      grp_avatar.className = "pfp";
      grp_avatar.src = data.avatar;
      grp_name = document.createElement("span");
      grp_name.innerHTML = "&nbsp;"+data.name;
      list_item.appendChild(grp_avatar);
      list_item.appendChild(grp_name);

      // "Add Member" Button
      if (data.owner) {
        adm_button = document.createElement("button");
        adm_image = new Image();
        adm_image.src = "../images/person-fill.svg";
        adm_button.appendChild(adm_image);
        adm_button.setAttribute("onclick", "add_to_group("+data.id+");");
        adm_button.className = "adm_button";
        grp_block.appendChild(adm_button);
      }

      // Add Elements to Page
      document.querySelector("#grp_messages").appendChild(grp_block);
      document.querySelector("#grp_list").appendChild(list_item);

      // Get messages for chat
      socket.send(JSON.stringify({
        action: "get_msgs",
        chat_id: data.id
      }));

      break;

    // Message
    case "msg":
      console.log(event.data);
      // Get chat block
      elems = document.getElementsByClassName("grp_block");
      for (i=0; i < elems.length; i++) {
        if (elems[i].dataset.groupId == data.groups_id) {
          // Create elements
          block = document.createElement("div");
          block.dataset.dateTime = data.date_time;
          block.className = "message";
          avatar = new Image();
          avatar.src = data.avatar;
          timeFromNow = document.createElement("span");
          timeFromNow.className = "message_time";
          timeFromNow.innerHTML = moment(data.date_time).fromNow();
          username = document.createElement("p");
          username.className = "message_username";
          username.appendChild(avatar);
          username.innerHTML += ("&nbsp;"+data.username+"&nbsp;");
          username.appendChild(timeFromNow);
          msg_content = document.createElement("p");
          msg_content.className = "message_content";
          msg_content.innerHTML = "&nbsp;"+data.message;
          block.appendChild(username);
          block.appendChild(msg_content);

          // Add Elements to Page
          elems[i].appendChild(block);

          // Scroll down
          elems[i].scrollTop = elems[i].scrollHeight;

          return;
        }
      }
      break;

    // User avatar
    case "avatar":
      pfp = new Image();
      pfp.src = data.url;
      document.querySelector(".avatar").appendChild(pfp);
      break;

    case "success":
      console.log("success");
      swal("Success!", data.message, "success");
      break;

    case "error":
      swal("Something went wrong", data.message, "error");
      break;




    case "friend":
      // Get right block
      if (data.type == "pending") block = document.getElementById("pending");
      else if (data.type == "inc") block = document.getElementById("inc");
      else block = document.getElementById("all");

      // Create elements
      friend_block = document.createElement("div");
      friend_block.className = "friend";
      friend_block.dataset.userId = data.user_id;
      avatar = new Image();
      avatar.src = data.avatar;
      username = document.createElement("span");
      username.innerHTML = "&nbsp;"+data.username;
      buttons = document.createElement("div");
      buttons.id = "buttons";
      button1 = document.createElement("button");
      button1.className = "accept";
      button1.innerHTML = "Accept";
      button1.setAttribute('onclick', 'accept(this);');
      button2 = document.createElement("button");
      button2.className = "remove";
      button2.innerHTML = "Remove";
      button2.setAttribute('onclick', 'remove(this);');

      // Append elements
      buttons.appendChild(button1);
      buttons.appendChild(button2);
      friend_block.appendChild(avatar);
      friend_block.appendChild(username);
      friend_block.appendChild(buttons);
      block.appendChild(friend_block);
  }
};

// Socket Closed
socket.onclose = function() {
  // Not logged in
  logged_in = false;

  // Reset DMs
  document.querySelector("#grp_messages").innerHTML = "";
  document.querySelector("#grp_list").innerHTML = '<li><a href="/friends"><img class="svg" src="/images/person.svg" alt="Person Icon" />&nbsp;Friends</a></li>';

  // Attempt to reconnect
  setTimeout(function(){connect();}, 1000);
}
}

// Function to find cookie by name
// https://www.w3schools.com/js/js_cookies.asp
function getCookie(cname) {
  var name = cname + "=";
  var decodedCookie = decodeURIComponent(document.cookie);
  var ca = decodedCookie.split(';');
  for(var i = 0; i <ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
return "";
}
