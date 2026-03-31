function show(id) {
    tab_content = document.getElementsByClassName("tab_content");
    for (i=0; i< tab_content.length; i++) {
        tab_content[i].style.display="none";
    }
    document.querySelector(id).style.display = "block";
}

function add_friend () {
    f_username = document.getElementById("add_friend_bar").value;
    document.getElementById("add_friend_bar").value="";
    socket.send(JSON.stringify({
        action: "friend_request",
        username: f_username
    }));
}

function accept(button) {
    user_id = button.parentElement.parentElement.dataset.userId;
    socket.send(JSON.stringify({
        action: "start_friendship",
        user_id: user_id
    }));
    button.parentElement.parentElement.remove();
}

function remove(button) {
    user_id = button.parentElement.parentElement.dataset.userId;
    socket.send(JSON.stringify({
        action: "reject_friend",
        user_id: user_id
    }));
    button.parentElement.parentElement.remove();
}