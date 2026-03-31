//FINDS THE COMMUNITY NAME USING THE COMMUNITY ID
    window.onload = function run_on_load() {
      community_name();
      set_join_btn();
      findposts();
      find_num_members();
      find_community_bio();
      setuseravatar();
    }

    function friends() {
      window.location.href = "../frontend/friends.php";
    }

    function community_name() {
      //GETS THE COMMUNITY ID FROM AN ELEMENT THAT GETS IT USING $_GET['id']
      var community_id = document.getElementById('community_id').innerHTML;
      var fd = new FormData();
      //SENDS THE COMMUNITY ID TO THE BACKEND
      fd.append("community_id", community_id);
      fd.append("community_find_name", true);
      var xhr = new XMLHttpRequest();
      var fullurl = '../backend/communitybackend.php';
        xhr.open('POST', fullurl, true);
        xhr.onload = function() {
          if (this.status == 200) {
            //PARSES THE JSON AND SETS THE COMMUNITY NAME
            console.log("Hello World");
            console.log(this.responseText);
            var community_name = JSON.parse(this.responseText);
            document.getElementById('community-title').innerHTML = community_name;
          };
      };
      xhr.send(fd);

    }

// FUNCTION THAT SCROLLS BACK UP WHEN YOU CLICK ON THE BACK TO TOP BUTTON
    function backToTop() {
      x = document.getElementById('return-to-top');
      x.style.display = "none";
      mainpage = document.getElementById('mainpage');
      mainpage.scrollTop = 0;
      x.style.display = "none";
    }

// FUNCTION THAT DISPLAYS THE BACK TO TOP BUTTON AND LOADS MORE POSTS
    function onscrollfunction() {
      if ((mainpage.scrollTop + mainpage.offsetHeight) == mainpage.scrollHeight) {
      findmoreposts()
      }
    if ((mainpage.scrollTop + mainpage.offsetHeight) >= 800) {
      x = document.getElementById('return-to-top');
      x.style.display = "block";
    }
  }

//FUNCTION THAT ACTIVATES WHEN THE USER CLICKS ON THE JOIN BUTTON
  function joinCommunity() {
    //FINDS THE COMMUNITY ID
   community_id = document.getElementById('community_id').innerHTML;
   var fd = new FormData();
   fd.append("community_id", community_id);
   fd.append("join_leave_community", true);
   var xhr = new XMLHttpRequest();
   //SENDS IT TO THE BACKEND
   var fullurl = '../backend/communitybackend.php';
   xhr.open('POST', fullurl, true);
   xhr.onload = function() {
     if (this.status == 200) {
       //TELLS THE USER IF THE OPERATION WAS SUCCESSFUL OR NOT
       var reponse = JSON.parse(this.responseText);
       alert(reponse);
       find_num_members();
       btn = document.getElementById('join_leave_btn');

      if (reponse == "You have joined!") {
        btn.innerHTML = "<span class='joined'>Joined</span><span class='leave'>Leave</span>";

      } else if (reponse == "You have left!") {
        btn.innerHTML = "Join"

      } else {

      }

     }
   }
   xhr.send(fd);
  }

//SETS THE JOIN BUTTON TO EITHER JOIN OR JOINED/LEAVE

  function set_join_btn() {
    //GETS THE COMMUNITY ID FROM AN ELEMENT THAT GETS IT USING $_GET['id']
    var community_id = document.getElementById('community_id').innerHTML
    var fd = new FormData();
    //SENDS THE COMMUNITY ID TO THE BACKEND
    fd.append("community_id", community_id);
    fd.append("set_join_btn", true);
    var xhr = new XMLHttpRequest();
    var fullurl = '../backend/communitybackend.php';
      xhr.open('POST', fullurl, true);
      xhr.onload = function() {
        if (this.status == 200) {
          //FINDS THE JOIN/LEAVE BUTTON
          btn = document.getElementById('join_leave_btn');
          //PARSES THE JSON AND SETS THE COMMUNITY NAME
          console.log(this.responseText);
          var user_community_status = JSON.parse(this.responseText);
          //LOGIC TO SET THE BUTTON
          if (user_community_status == "this user is a member of the given community") {
            btn.innerHTML = "<span class='joined'>Joined</span><span class='leave'>Leave</span>";
          } else {
            btn.innerHTML = "Join"
          }

        };
    };
    xhr.send(fd);

  }


//FUNCTION THAT TAKES IN A BIG ARRAY AND A COMMUNITY_ID THEN ECHOES OUT THE POSTS
  function echo_posts(bigarray, community_id) {
    for (i in bigarray) {
      //EXTRACTING ALL THE IMPORTANT VALUES AND SETTING THEM AS VARIALBES TO EXECUTE LOGIC ON THEM LATER

      var title = bigarray[i]["title"];
      var post_id = bigarray[i]["community_contents_id"];
      var post = bigarray[i]["post"];
      var pic_or_vid_src = bigarray[i]["pic_or_vid"];
      var filetype = bigarray[i]["filetype"];
      var sender_id = bigarray[i]["user_id"];
      var sender_username = bigarray[i]["username"];
      var likes = bigarray[i]["numberoflikes"];
      var dislikes = bigarray[i]["numberofdislikes"];
      var date_time = bigarray[i]["date_time"];

      offset = moment().utcOffset();
      d = moment(date_time).add(offset, 'minutes');
      difference = moment(d, "YYYY-MM-DD HH:mm:ss").fromNow();


      //FINDS THE DIV TO PUT ALL THE POSTS IN
      element = document.getElementById('posts');


      //CREATES A DIV FOR THE POST
      post_div = document.createElement("div");
      post_div.classList.add("post-div");
      post_div.id = post_id;


      grey_post_div = document.createElement("div");
      grey_post_div.classList.add("grey-div");

      //CREATES THE TEXT TO SAY : POSTED 2 HOURS AGO
      time = document.createElement("p");
      time.classList.add("time");
      time_text = document.createTextNode("Posted " + difference);
      time.appendChild(time_text);
      //PUTS THE TIME INTO THE POST'S DIV
      grey_post_div.appendChild(time);


      //CREATES THE TITLE OF THE POST
      title_display = document.createElement("h1");
      title_display.classList.add("title");
      title_text = document.createTextNode(title);
      title_display.appendChild(title_text);
      //PUTS THE TITLE INTO THE POST'S DIV
      grey_post_div.appendChild(title_display);


      //CREATES THE SENDER OF THE POST AND DISPLAYS IT
      sender = document.createElement("div");
      sender.classList.add("sender");
      sender_text = document.createTextNode("Posted by : " + sender_username);
      sender.appendChild(sender_text);
      //PUTS THE SENDER INTO THE POST'S DIV
      grey_post_div.appendChild(sender);


      //CREATED THE BUTTON DIVS FOR THE LIKE BUTTON AND DISLIKE BUTTON
      button_div = document.createElement("div");
      button_div.classList.add("like_buttons");

      //LIKE BUTTON
      like_button = document.createElement("button");
      like_button.classList.add("likes");
      like_button.classList.add("fas");
      like_button.classList.add("fa-thumbs-up");
      like_button.setAttribute("onclick","community_like(true, " + post_id + ")");
      like_button.id = "like_btn_" + post_id;
      //SPAN WITH NUMBER OF LIKES
      num_of_likes = document.createElement("span");
      num_of_likes.classList.add("like-dislike");
      num_of_likes.id = "likes_" + post_id;
      //CREATES TEXT WITH NUMBER OF LIKES
      likes_text = document.createTextNode(likes);
      //ADDS TEXT TO SPAN
      num_of_likes.appendChild(likes_text);


      //DISLIKE BUTTON
      dislike_button = document.createElement("button");
      dislike_button.classList.add("likes");
      dislike_button.classList.add("fas");
      dislike_button.classList.add("fa-thumbs-down");
      dislike_button.setAttribute("onclick","community_like(false, " + post_id + ")");
      dislike_button.id = "dislike_btn_" + post_id;
      //SPAN WITH NUMBER OF DISLIKES
      num_of_dislikes = document.createElement("span");
      num_of_dislikes.classList.add("like-dislike");
      num_of_dislikes.id = "dislike_" + post_id;
      //CREATES TEXT WITH NUMBER OF DISLIKES
      dislikes_text = document.createTextNode(dislikes);
      //ADDS TEXT TO SPAN
      num_of_dislikes.appendChild(dislikes_text);


      //ADDS THE LIKE + DISLIKE BUTTONS AND SPANS TO A MAIN DIV
      button_div.appendChild(like_button);
      button_div.appendChild(num_of_likes);
      button_div.appendChild(dislike_button);
      button_div.appendChild(num_of_dislikes);

      //PUTS THE LIKE AND DISLIKE BUTTONS INTO THE POST'S DIV
      grey_post_div.appendChild(button_div);


      //IF THERE IS TEXT THEN PRINT IT OUT :
      if (filetype == "null" || filetype == "textImage" || filetype == "textVideo") {
        //CREATES THE POST P TAG AND PUT THE TEXT INTO IT
        post_display = document.createElement("p");
        post_display.classList.add("post");
        post_text = document.createTextNode(post);
        post_display.appendChild(post_text);
        //PUTS THE TEXT POST INTO THE POST'S DIV
        grey_post_div.appendChild(post_display);

        turnlikebuttonsblue(post_id);
      }


      if (filetype == "video" || filetype == "textVideo") {
        //CREATES THE VIDEO TAG AND PUTS THE VIDEO INTO IT
        video_display = document.createElement("video");
        video_display.classList.add("video");
        video_display.setAttribute("src", pic_or_vid_src);
        video_display.setAttribute("controls","");
        //PUTS THE VIDEO INTO THE POST'S DIV
        grey_post_div.appendChild(video_display);

      }


      if (filetype == "image" || filetype == "textImage") {
        //CREATES THE IMAGE TAG AND PUTS THE IMAGE INTO IT
        image_display = document.createElement("img");
        image_display.classList.add("image");
        image_display.setAttribute("src", pic_or_vid_src);
        //PUTS THE IMAGE INTO THE POST'S DIV
        grey_post_div.appendChild(image_display);

      }





      //COMMENTS APPEAR AND COMMENT SECTION
      comment_div = document.createElement("div");
      comment_div.classList.add("comments");

      //BUTTON THAT MAKES COMMENTS APPEAR
      see_all_comments = document.createElement("p");
      see_all_comments.classList.add("see_all_comments");
      see_all_comments.setAttribute("onclick","commentsAppear(" + post_id + ", " + community_id + ")");
      comments_text = document.createTextNode("See all comments");

      //PUTS THE BUTTON INTO THE COMMENT SECTION DIV
      see_all_comments.appendChild(comments_text);
      comment_div.appendChild(see_all_comments);
      //PUTS THE COMMENTS INTO THE POST'S DIV



      post_div.appendChild(grey_post_div);
      post_div.appendChild(comment_div);
      //PUTS THE POST INTO THE FRONTEND PAGE

      element.appendChild(post_div);

  }
  }







 //FUNCTION FINDS AND DISPLAYS 20 POSTS FROM THE COMMUNITY
  function findposts() {

    var community_id = document.getElementById('community_id').innerHTML;
    var fd = new FormData();
    //SENDS THE COMMUNITY ID TO THE BACKEND
    fd.append("community_id", community_id);
    fd.append("findposts", true);
    var xhr = new XMLHttpRequest();
    var fullurl = '../backend/communitybackend.php';
    xhr.open('POST', fullurl, true);
      xhr.onload = function() {
        if (this.status == 200) {
          //DELETES ALL THE POST THAT WERE ALREADY THERE IN CASE THERE WERE ALREADY SOME
          i = document.getElementsByClassName("post-div");

          while (i[0]) {
          i[0].parentNode.removeChild(i[0]);
        };
        //PARSING THE ARRAY RETURNED BY THE BACKEND
          bigarray = JSON.parse(this.responseText);
          console.log(bigarray);
          echo_posts(bigarray, community_id);
    }

}
xhr.send(fd);

}


//LOADS POSTS ONCE YOU SCROLL TO THE BOTTOM OF THE PAGE
function findmoreposts() {

  sortby = document.getElementById('sortByStatus').innerHTML;
  last_post_id = document.getElementsByClassName('post-div')[document.getElementsByClassName('post-div').length - 1].id;
  community_id = document.getElementById('community_id').innerHTML;
  scrollTop = document.getElementById('mainpage').scrollTop;

  var fd = new FormData();
  fd.append("sortby", sortby);
  fd.append("last_post_id", last_post_id);
  fd.append("community_id", community_id);
  fd.append("load_more_posts", true);

  var xhr = new XMLHttpRequest();
  var fullurl = '../backend/communitybackend.php';
  xhr.open('POST', fullurl, true);
  xhr.onload = function() {
    if (this.status == 200) {
      //PARSING THE ARRAY RETURNED BY THE BACKEND
      bigarray = JSON.parse(this.responseText);
      echo_posts(bigarray, community_id);
      document.getElementById('mainpage').scrollTop = scrollTop;
    };
  };
  xhr.send(fd);

}

//FUNCTION THAT MAKES THE FILE ATTACHEMENT WORK, ADDS EVENT LISTENERS AND REMOVES THEM
function check() {
  document.querySelector('#file').removeEventListener('change', Myhandlerfunction, true);
  document.querySelector('#file').addEventListener('change', Myhandlerfunction , true);
}

//FUNCTION FINDS THE FIRST 20 POSTS OR THE LAST 20 POSTS
function sortBy(e) {

  community_id = document.getElementById('community_id').innerHTML;
  post_area = document.getElementById('posts');


   if (e == "newest") {

    var fd = new FormData();
    fd.append("sorting", true);
    fd.append("community_id", community_id);
    fd.append("newest", true);
    var xhr = new XMLHttpRequest();
    var fullurl = '../backend/communitybackend.php';
    xhr.open('POST', fullurl, true);
    xhr.onload = function() {
      if (this.status == 200) {
        //SETS THE SORT BY STATUS TO NEWEST
       document.getElementById('sortByStatus').innerHTML = "newest";


       //REMOVES THE POSTS THAT WERE THERE

       i = document.getElementsByClassName("post-div");

       while (i[0]) {
       i[0].parentNode.removeChild(i[0]);
     };
     bigarray = JSON.parse(this.responseText);
     echo_posts(bigarray, community_id);

      }
    }
    xhr.send(fd);

  } else if (e == "oldest") {

    var fd = new FormData();
    fd.append("sorting", true);
    fd.append("community_id", community_id);
    fd.append("oldest", true);
    var xhr = new XMLHttpRequest();
    var fullurl = '../backend/communitybackend.php';
    xhr.open('POST', fullurl, true);
    xhr.onload = function() {
      if (this.status == 200) {
        //SETS THE SORT BY STATUS TO OLDEST
        document.getElementById('sortByStatus').innerHTML = "oldest";

        //REMOVES THE POSTS THAT WERE THERE

        i = document.getElementsByClassName("post-div");

        while (i[0]) {
        i[0].parentNode.removeChild(i[0]);
      };

        //PARSES THE INCOMING ARRAY
        console.log(this.responseText);
        bigarray = JSON.parse(this.responseText);
        echo_posts(bigarray, community_id);
      }
    }
    xhr.send(fd);

  }
};

//FUNCTION THAT MAKES THE COMMENTS APPEAR
function commentsAppear(id, community_id) {

  var fd = new FormData();
  fd.append("post_id", id);
  fd.append("community_id", community_id);
  fd.append("comments_appear", true)
  var xhr = new XMLHttpRequest();
  var fullurl = '../backend/communitybackend.php';
  xhr.open('POST', fullurl, true);
  xhr.onload = function() {
    if (this.status == 200) {
      console.log(this.responseText);
      bigarray = JSON.parse(this.responseText);

      //CREATES THE DIV TO PUT ALL THE COMMENTS IN
      all_comments_div = document.createElement("div");

      //CREATES THE BUTTON TO MAKE THE COMMENTS DISSAPEAR
      comment_dissapear = document.createElement("p");
      comment_dissapear.classList.add("close");
      comment_dissapear.setAttribute("onclick","commentsDisappear(" + id + ", " + community_id + ")");
      comment_dissapear_text = document.createTextNode("Hide all comments");
      comment_dissapear.appendChild(comment_dissapear_text);

      //CREATES THE TEXT AREA TO WRITE A COMMENT
      send_comment = document.createElement("textarea");
      send_comment.classList.add("comment-send");
      send_comment.setAttribute("placeholder","What do you think about this post?");
      send_comment.setAttribute("name","comment");

      //CREATES THE BUTTON TO SEND A COMMENT
      comment_submit_button = document.createElement("button");
      comment_submit_button.classList.add("comment-submit-button");
      comment_submit_button.setAttribute("name","comment-submit");
      comment_submit_button.setAttribute("onclick","sendcomment(" + id + ", " + community_id + ")");
      comment_submit_button_text = document.createTextNode("Comment");
      comment_submit_button.appendChild(comment_submit_button_text);

      //CREATES TEXT TO SHOW THAT THE FOLLOWING THINGS ARE COMMENTS
      comments_title = document.createElement("p");
      comments_title.classList.add("these_are_the_comments");
      comments_title_text = document.createTextNode("Comments: ");
      comments_title.appendChild(comments_title_text);

      for (i in bigarray) {

        var post_id = bigarray[i]["post_id"];
        var comment_id = bigarray[i]["community_comments_id"];
        var community_id_comment = bigarray[i]["community_id"];
        var date_time = bigarray[i]["date_time"];
        var comment = bigarray[i]["comment"];
        var sender_id = bigarray[i]["user_id"];
        var sender_username = bigarray[i]["username"];


        offset = moment().utcOffset();
        d = moment(date_time).add(offset, 'minutes');
        difference = moment(d, "YYYY-MM-DD HH:mm:ss").fromNow();

        //CREATES A CONTAINER FOR EACH COMMENT
        comment_container = document.createElement("div");
        comment_container.classList.add("comment_container");

        //CREATES ADDS THE USERNAME OF THE SENDER TO THE COMMENT
        comment_sender = document.createElement("p");
        comment_sender.classList.add("comment_sender");
        comment_sender_text = document.createTextNode(sender_username);
        comment_sender.appendChild(comment_sender_text);

        //ADDS THE SECTION TO THE CONTAINER FOR EACH COMMENT
        comment_container.appendChild(comment_sender);


        //ADDS THE "POSTED 5 HOURS AGO" TEXT INTO TO THE COMMENT
        comment_post_time = document.createElement("p");
        comment_post_time.classList.add("comment_post_time");
        comment_post_time_text = document.createTextNode("Commented " + difference);
        comment_post_time.appendChild(comment_post_time_text);

        //ADDS THE SECTION TO THE CONTAINER FOR EACH COMMENT
        comment_container.appendChild(comment_post_time);


        //ADDS THE COMMENT TEXT TO THE COMMENT
        comment_content = document.createElement("p");
        comment_content.classList.add("comment-content");
        comment_content_text = document.createTextNode(comment);
        comment_content.appendChild(comment_content_text);

        //ADDS THE SECTION TO THE CONTAINER FOR EACH COMMENT
        comment_container.appendChild(comment_content);

        //ADDS THE FINAL PRODUCT/ THE COMMENT TO THE DIV WITH ALL THE COMMENTS
        all_comments_div.appendChild(comment_container);
      }


      //FINDS THE COMMENT DIV IN THE POST AND DELETES WHATEVER WAS THERE
       document.getElementById(id).getElementsByClassName('comments')[0].innerHTML = "";
       comment_div = document.getElementById(id).getElementsByClassName('comments')[0];


       //ADDS THE ELEMENTS THAT HAVE BEEN PREPRED TO THE FRONTEND PAGE
       comment_div.appendChild(comment_dissapear);
       comment_div.appendChild(send_comment);
       comment_div.appendChild(comment_submit_button);
       comment_div.appendChild(comments_title);
       comment_div.appendChild(all_comments_div);


    }
  }
  xhr.send(fd);
}

//FUNCTION THAT HIDES THE COMMENTS
function commentsDisappear(id, community_id) {

  console.log("id =" + id);
  //DELETES WHATEVER WAS PREVIOUSLY IN THE DIV
  document.getElementById(id).getElementsByClassName('comments')[0].innerHTML = "";


  //CREATES THE BUTTON TO MAKE THE COMMENTS RE-APPEAR
  comment_appear = document.createElement("p");
  comment_appear.classList.add("see_all_comments");
  comment_appear.setAttribute("onclick","commentsAppear(" + id + ", " + community_id + ")");
  comment_appear_text = document.createTextNode("See all comments");
  comment_appear.appendChild(comment_appear_text);

  // PUTS THE BUTTON INTO THE FRONTEND
  document.getElementById(id).getElementsByClassName('comments')[0].appendChild(comment_appear);

}


//FUNCTION THAT SENDS A COMMENT
function sendcomment(post_id, community_id) {

  comment_content = document.getElementById(post_id).getElementsByClassName('comment-send')[0];
  //CHECKS IF THE COMMENT CONTENT IS VALID
  if (comment_content.value == "") {
    alert("You have to to have something in your comment to send it!");
  } else if (comment_content.value.length > 2000) {
    alert("Sorry but your comment is too long! It must be less than 2000 characters!");
  } else {

    var fd = new FormData();
    fd.append("post_id", post_id);
    fd.append("community_id", community_id);
    fd.append("comment_content", comment_content.value);
    fd.append("send_comment", true);
    var xhr = new XMLHttpRequest();
    var fullurl = '../backend/communitybackend.php';
    xhr.open('POST', fullurl, true);
    xhr.onload = function() {
      if (this.status == 200) {
        //SENDS COMMENT
        if (this.responseText == "You must be logged in to comment!") {
          alert(this.responseText);
        }
        commentsAppear(post_id, community_id);

      }
    }
    xhr.send(fd);

  }
}


function community_like(true_or_false, post_id) {

  community_id = document.getElementById('community_id').innerHTML;
  if (true_or_false == true) {
    //CHECKS IS THE USER HAS CLICKED ON LIKE
    like_or_dislike = "like";

  }

  if (true_or_false == false) {
    //CHECKS IS THE USER HAS CLICKED ON DISLIKE
    like_or_dislike = "dislike";

  }

  var fd = new FormData();
  fd.append("post_id", post_id);
  fd.append("community_id", community_id);
  fd.append("like_or_dislike", like_or_dislike);
  fd.append("likedislike", true);
  var xhr = new XMLHttpRequest();
  var fullurl = '../backend/communitybackend.php';
  xhr.open('POST', fullurl, true);
  xhr.onload = function() {
    if (this.status == 200) {

      console.log(this.responseText);


      //ADDS 1 LIKE TO THE POST
      if (this.responseText == "successfully liked post") {
        document.getElementById("likes_" + post_id).innerHTML -= -1;
        document.getElementById("like_btn_" + post_id).style.color = "#0984E3";
      }
      //ADDS 1 LIKE TO THE POST
      if (this.responseText == "successfully unliked post") {
        document.getElementById("likes_" + post_id).innerHTML -= 1;
        document.getElementById("like_btn_" + post_id).style.color = "transparent";
      }
      //ADDS 1 DISLIKE TO THE POST
      if (this.responseText == "successfully disliked post") {
        document.getElementById("dislike_" + post_id).innerHTML -= -1;
        document.getElementById("dislike_btn_" + post_id).style.color = "#0984E3";
      }
      //ADDS 1 DISLIKE TO THE POST
      if (this.responseText == "successfully undisliked post") {
        document.getElementById("dislike_" + post_id).innerHTML -= 1;
        document.getElementById("dislike_btn_" + post_id).style.color = "transparent";
      }



    }
  }
  xhr.send(fd);




}



function postIncoming() {
  title = document.getElementById('title').value;
  post = document.getElementById('post').value;
  filePath = document.getElementById("file").value;
  console.log(title);
  console.log(post);
  console.log(filePath);
  community_id = document.getElementById('community_id').innerHTML;
  if (title == "") {
    alert("You must add a title to your post!");
  }
  var allowedExtensions =
                    /(\.jpg|\.jpeg|\.png|\.mp4|\.mov|\.JPG|\.JPEG|\.PNG|\.MP4|\.MOV)$/i;

 if (post.length > 4000) {
   alert("Sorry, this is too long. Your post has to be less than 4000 characters long!");
 } else if (title.length > 50) {
   alert("Sorry, this is too long. Your title has to be less than 50 characters long!");
 } else {
   if (title != "" && post != "" && filePath == "") {
     community_id = document.getElementById('community_id').innerHTML;
     var fd = new FormData();
     fd.append("sending", true);
     fd.append("title", title);
     fd.append("post", post);
     fd.append("community_id", community_id);
     fd.append("textPost", true);
     var xhr = new XMLHttpRequest();
     community_id = document.getElementById('community_id').innerHTML;
     var fullurl = '../backend/communitybackend.php';
       xhr.open('POST', fullurl, true);
       xhr.onload = function() {
         if (this.status == 200) {
           console.log(this.responseText);
           findposts();
          document.getElementById('title').value = "";
          document.getElementById('post').value = "";
          document.getElementById('file').value = "";
          if (this.responseText == "") {
            alert("Sorry, you must wait 15 mins before posting again!");
          } else if (this.responseText == "Sorry, you must wait 15 mins before posting again!") {
            alert(this.responseText);
          } else if (this.responseText == "You must join the community if want to post in it!") {
            alert(this.responseText);
          }
         };
     };
     xhr.send(fd);
   } else if (title != "" && post != "" && filePath != "") {
     community_id = document.getElementById('community_id').innerHTML;
     x = document.getElementById('file');
     file = x.files[0];
     fsize = file.size;
     filePath = document.getElementById("file").value;
     var filesize = Math.round((fsize/1000));
     if (filesize > 20000) {
       alert("This file is too big, it must be 20MB or less to be posted");
     } else if (!allowedExtensions.exec(filePath)) {
       alert("This file is of the wrong exetension, please send a .jpg, .jpeg, .png, .mp4, .mov");
     } else {
       var fd = new FormData();
       fd.append("sending", true);
       fd.append("title", title);
       fd.append("post", post);
       fd.append("file", file);
       fd.append("community_id", community_id);
       fd.append("textFilePost", true);
       var xhr = new XMLHttpRequest();
       community_id = document.getElementById('community_id').innerHTML;
       var fullurl = '../backend/communitybackend.php';
       xhr.open('POST', fullurl, true);
       xhr.onload = function() {
         if (this.status == 200) {
           console.log(this.responseText);
           findposts();
           document.getElementById('title').value = "";
           document.getElementById('post').value = "";
           document.getElementById('file').value = "";
           if (this.responseText == "") {
             alert("Sorry, you must wait 15 mins before posting again!");
           }  else if (this.responseText == "Sorry, you must wait 15 mins before posting again!") {
             alert(this.responseText);
           } else if (this.responseText == "You must join the community if want to post in it!") {
             alert(this.responseText);
           }
         };
     };
     xhr.send(fd);
     }

   } else if (title != "" && post == "" && filePath != "") {
     community_id = document.getElementById('community_id').innerHTML;
     x = document.getElementById('file');
     file = x.files[0];
     fsize = file.size;
     filePath = document.getElementById("file").value;
     var filesize = Math.round((fsize/1000));
     if (filesize > 20000) {
       alert("This file is too big, it must be 20MB or less to be posted");
     } else if (!allowedExtensions.exec(filePath)) {
       alert("This file is of the wrong exetension, please send a .jpg, .jpeg, .png, .mp4, .mov");
     } else {
       var fd = new FormData();
       fd.append("sending", true);
       fd.append("title", title);
       fd.append("post", post);
       fd.append("file", file);
       fd.append("community_id", community_id);
       fd.append("filePost", true);
       var xhr = new XMLHttpRequest();
       community_id = document.getElementById('community_id').innerHTML;
       var fullurl = '../backend/communitybackend.php';
       xhr.open('POST', fullurl, true);
       xhr.onload = function() {
         if (this.status == 200) {
           console.log(this.responseText);
           findposts();
           document.getElementById('title').value = "";
           document.getElementById('post').value = "";
           document.getElementById('file').value = "";

           if (this.responseText == "") {
             alert("Sorry, you must wait 15 mins before posting again!");
           } else if (this.responseText == "Sorry, you must wait 15 mins before posting again!") {
             alert(this.responseText);
           } else if (this.responseText == "You must join the community if want to post in it!") {
             alert(this.responseText);
           }
         };
     };
     xhr.send(fd);
     }
   }
 }



}

//FUNCTION FINDS THE NUMBER OF MEMBERS IN A COMMUNITY AND THEN ADDS IT TO THE FRONTEND
function find_num_members() {
  community_id = document.getElementById('community_id').innerHTML;

  var fd = new FormData();
  fd.append("community_id", community_id);
  fd.append("numofmembers", true);
  var xhr = new XMLHttpRequest();
  var fullurl = '../backend/communitybackend.php';
  xhr.open('POST', fullurl, true);
  xhr.onload = function() {
    if (this.status == 200) {
      numberofmembers = this.responseText;
      document.getElementById('members_number').innerHTML = numberofmembers;
    };
};
xhr.send(fd);



}

//FUNCTION FINDS THE COMMUNITY BIO TO ADD IT TO THE FRONTEND
function find_community_bio() {
  community_id = document.getElementById('community_id').innerHTML;


  var fd = new FormData();
  fd.append("community_id", community_id);
  fd.append("communitybio", true);
  var xhr = new XMLHttpRequest();
  var fullurl = '../backend/communitybackend.php';
  xhr.open('POST', fullurl, true);
  xhr.onload = function() {
    if (this.status == 200) {
      community_bio = this.responseText;
      document.getElementById('community_bio').innerHTML = community_bio;
    };
};
xhr.send(fd);





}

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

function turnlikebuttonsblue(post_id) {
  var fd = new FormData();
  fd.append("post_id", post_id);
  fd.append("likebuttonsblue", true);
  var xhr = new XMLHttpRequest();
  var fullurl = '../backend/communitybackend.php';
  xhr.open('POST', fullurl, true);
  xhr.onload = function() {
    if (this.status == 200) {
      console.log(this.responseText);

      if (this.responseText == "dislike") {
        document.getElementById("dislike_btn_" + post_id).style.color = "#0984E3";
      } else if (this.responseText == "like") {
        document.getElementById("like_btn_" + post_id).style.color = "#0984E3";

      }

    };
};
xhr.send(fd);
}

//FUNCTION SETS THE USER'S AVATAR IN THE UI
function setuseravatar() {
  var fd = new FormData();
  fd.append("useravatar", true);
  var xhr = new XMLHttpRequest();
  var fullurl = '../backend/communitybackend.php';
  xhr.open('POST', fullurl, true);
  xhr.onload = function() {
    if (this.status == 200) {
      console.log(this.responseText);
      if (this.responseText == "not logged in") {
        document.getElementById("user-avatar").setAttribute("style","background-image: url(../images/blankprofilepicture.png)");
      } else {
        document.getElementById("user-avatar").setAttribute("style","background-image: url('" + this.responseText + "')");
      }

    };
};
xhr.send(fd);
}

function profile() {
  window.location.href = "../frontend/profile.php";
}
