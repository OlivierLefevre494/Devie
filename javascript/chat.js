// Update time tags every 10seconds
setInterval(function() {
    messages = document.getElementsByClassName("message");
    for (i=0; i < messages.length; i++) {
        username_tag = messages[i].children[0];
        time_tag = username_tag.childNodes[2];
        time_tag.innerHTML = moment(messages[i].dataset.dateTime).fromNow();
    }
}, 10000);

// Display Selected Chat
function open_chat(chat_id, group_block) {
    // Set current chat
    current_chat = chat_id;

    // Get chat div
    elems = document.getElementsByClassName("grp_block");
    for (i=0; i < elems.length; i++) {
      if (elems[i].dataset.groupId == chat_id) {
        // Scroll down
        elems[i].style.display = "block";
        elems[i].scrollTop = elems[i].scrollHeight;
      }
      else elems[i].style.display = "none";
    }

    // Highlight menu group
    groups_menu = document.getElementsByClassName("group_selected");
    for (i=0; i < groups_menu.length; i++) {
      groups_menu[i].className = "";
    }
    group_block.className = "group_selected";
  }

  // Send message
  function send() {
    // Get Message Bar Value
    message = document.querySelector("#message_bar").value;

    // Check if message empty
    if (current_chat != 0 && message != "") {
      // Send message to server
      socket.send(JSON.stringify({
        action: "msg",
        chat_id: current_chat,
        content: message
      }));

      // Reset Message Bar
      document.querySelector("#message_bar").value = "";
    }
  }

// Create group
function create_group() {
    swal({
        text: "Create a group!",
        content: "input"
    }).then(group_name=> {
        socket.send(JSON.stringify({
            action: "new_grp",
            content: group_name
        }));
    });
}

// Add user to group
function add_to_group(id) {
    swal({
        text: "Add a friend to your group!",
        content: "input"
    }).then(username=> {
        socket.send(JSON.stringify({
            action: "add_to_grp",
            chat_id: id,
            new_user: username
        }));
    });
}

window.onload = function () {
  minitutorial();
}


function minitutorial() {
  console.log("hi");
  //Checking if user is here for the first time
  var fd = new FormData();
  fd.append("checkiffirsttimeuser", true);
  var xhr = new XMLHttpRequest();
  var fullurl = '../backend/chat-backend.php';
    xhr.open('POST', fullurl, true);
    xhr.onload = function() {
      if (this.status == 200) {
        console.log(this.responseText);
        if (this.responseText == "yes") {


          header = document.getElementById('header');
          dropdown_avatar = document.getElementById('dropdown');
          sidebar = document.getElementById('sidebar');
          messaging_link = document.getElementById('messaging_link');
          community_link = document.getElementById('community_link');
          gaming_link = document.getElementById('gaming_link');
          messages = document.getElementById('grp_messages');
          msg_input = document.getElementById('input');
          grp_menu = document.getElementById('grp_menu');
          friends_link = document.getElementById('friends_link');

          tutorial_text_div = document.createElement("div");
          tutorial_text_div.classList.add("tutorial-text-div");

          tutorial_text_p = document.createElement("p");
          tutorial_text_p.classList.add("tutorial-text");
          tutorial_text1 = document.createTextNode("This is a tutorial on how to use Devie!");
          tutorial_text_p.appendChild(tutorial_text1);

          var br = document.createElement("br");
          tutorial_text_p.appendChild(br);

          tutorial_text2 = document.createTextNode(" [ Click to continue ].");
          tutorial_text_p.appendChild(tutorial_text2);

          tutorial_text_div.appendChild(tutorial_text_p);

          document.body.append(tutorial_text_div);


          function addblurredclass(element) {
            element.classList.add("blurred");
          }
          function removeblurredclass(element) {
            element.classList.remove("blurred");
          }

          addblurredclass(header);
          addblurredclass(dropdown_avatar);
          addblurredclass(sidebar);
          addblurredclass(messaging_link);
          addblurredclass(community_link);
          addblurredclass(gaming_link);
          addblurredclass(messages);
          addblurredclass(msg_input);
          addblurredclass(grp_menu);
          addblurredclass(friends_link);

          var el = document;
          var tutorial_phase = 0;
          el.onclick = function () {


            if (tutorial_phase == 0) {
              removeblurredclass(sidebar);
              removeblurredclass(messaging_link);
              tutorial_phase = 1;
              tutorial_text_p.innerHTML = "On the left sidebar you can see a button that will bring you to this home page! <br>  [ Click to continue ].";
            }
            else if (tutorial_phase == 1) {
              addblurredclass(messaging_link);
              removeblurredclass(community_link);
              tutorial_phase = 2;
              tutorial_text_p.innerHTML = "This other button will bring you to Devie's forums! <br>  [ Click to continue ].";
            }
            else if (tutorial_phase == 2) {
              addblurredclass(community_link);
              removeblurredclass(gaming_link);
              tutorial_phase = 3;
              tutorial_text_p.innerHTML = "This last button will bring you to Devie's games! <br>  [ Click to continue ].";
            }
            else if (tutorial_phase == 3) {
              addblurredclass(sidebar);
              addblurredclass(gaming_link);
              removeblurredclass(grp_menu);
              removeblurredclass(friends_link);
              tutorial_phase = 4;
              tutorial_text_p.innerHTML = "Here you can create groups, invite your friends and then message them! <br>  [ Click to continue ].";
            }
            else if (tutorial_phase == 4) {
              addblurredclass(grp_menu);
              removeblurredclass(messages);
              removeblurredclass(header);
              removeblurredclass(dropdown_avatar);
              tutorial_phase = 5;
              tutorial_text_p.innerHTML = "Here you can set up your profile and settings, you can also logout and go to your friends page. Hover over your profile picture to see options. <br>  [ Click to continue ].";
            }
            else if (tutorial_phase == 5) {
              addblurredclass(messages);
              addblurredclass(header);
              addblurredclass(dropdown_avatar);
              tutorial_phase = 6;
              tutorial_text_p.innerHTML = "Thanks for finishing this tutorial, have fun! <br>  [ Click to continue ].";
            }
            else if (tutorial_phase == 6) {
              tutorial_text_div.remove();
              removeblurredclass(header);
              removeblurredclass(dropdown_avatar);
              removeblurredclass(sidebar);
              removeblurredclass(messaging_link);
              removeblurredclass(community_link);
              removeblurredclass(gaming_link);
              removeblurredclass(messages);
              removeblurredclass(msg_input);
              removeblurredclass(grp_menu);
              removeblurredclass(friends_link);

              var fd = new FormData();
              fd.append("finishedtutorial", true);
              var xhr = new XMLHttpRequest();
              var fullurl = '../backend/chat-backend.php';
                xhr.open('POST', fullurl, true);
                xhr.onload = function() {
                  if (this.status == 200) {
                  };
              };
              xhr.send(fd);

            }



          }

        } else {

        }

      };
  };
  xhr.send(fd);
}
