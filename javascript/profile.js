window.onload = function run_on_load() {
	setuseravatar();
	findmyposts();
  findusername();
  setuserbio();
  setprofilepic();
}

function friends() {
	window.location.href = "../frontend/friends.php";
}

// Function to find cookie by name
// Taken from https://www.w3schools.com/js/js_cookies.asp
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

	function checkifuserloggedin() {
		if (getCookie(token) == "") {
			window.location.href="../frontend/login.php";
		}
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
      findmoreofmyposts();
      }
    if ((mainpage.scrollTop + mainpage.offsetHeight) >= 800) {
      x = document.getElementById('return-to-top');
      x.style.display = "block";
    }
  }

  //FUNCTION SETS THE USER'S AVATAR IN THE UI
  function setuseravatar() {
    var fd = new FormData();
    fd.append("useravatar", true);
    var xhr = new XMLHttpRequest();
    var fullurl = '../backend/gamesearch.php';
    xhr.open('POST', fullurl, true);
    xhr.onload = function() {
      if (this.status == 200) {
        console.log(this.responseText);
        if (this.responseText == "not logged in") {
          document.getElementById("user-avatar").setAttribute("style","background-image: url('../images/blankprofilepicture.png')");
        } else {
          document.getElementById("user-avatar").setAttribute("style","background-image: url('" + this.responseText + "')");
        }

      };
  };
  xhr.send(fd);
  }



  function findmyposts() {

    var fd = new FormData();
    fd.append("findmyposts", true);
    var xhr = new XMLHttpRequest();
    var fullurl = '../backend/profilebackend.php';
    xhr.open('POST', fullurl, true);
    xhr.onload = function() {
      if (this.status == 200) {

        console.log(this.responseText);
        place = document.getElementById('post-div');
        bigarray = JSON.parse(this.responseText);
        echo_posts(bigarray, place);
				last_post_id = document.getElementsByClassName('post-div')[document.getElementsByClassName('post-div').length - 1];
				if (last_post_id == undefined) {
					console.log("finally");
          funnymessage = document.createElement("p");
          funnymessage.classList.add("funnymessage");
          funnymessagetext = document.createTextNode("Wow, it's pretty empty around here... Empty like space, no posts in sight...");
          funnymessage.appendChild(funnymessagetext);

          place.appendChild(funnymessage);
        }
      };
  };
  xhr.send(fd);

  }


  function echo_posts(bigarray, place) {
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
      var community_id = bigarray[i]["community_id"];

      offset = moment().utcOffset();
      d = moment(date_time).add(offset, 'minutes');
      difference = moment(d, "YYYY-MM-DD HH:mm:ss").fromNow();




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

      place.appendChild(post_div);

  }
  }

  function turnlikebuttonsblue(post_id) {
    var fd = new FormData();
    fd.append("post_id", post_id);
    fd.append("likebuttonsblue", true);
    var xhr = new XMLHttpRequest();
    var fullurl = '../backend/profilebackend.php';
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

  //FUNCTION THAT MAKES THE COMMENTS APPEAR
  function commentsAppear(id, community_id) {

    var fd = new FormData();
    fd.append("post_id", id);
    fd.append("community_id", community_id);
    fd.append("comments_appear", true)
    var xhr = new XMLHttpRequest();
    var fullurl = '../backend/profilebackend.php';
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
      var fullurl = '../backend/profilebackend.php';
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
      console.log("like");
      like_or_dislike = "like";

    }

    if (true_or_false == false) {
      //CHECKS IS THE USER HAS CLICKED ON DISLIKE
      console.log("dislike");
      like_or_dislike = "dislike";

    }

    var fd = new FormData();
    fd.append("post_id", post_id);
    fd.append("community_id", community_id);
    fd.append("like_or_dislike", like_or_dislike);
    fd.append("likedislike", true);
    var xhr = new XMLHttpRequest();
    var fullurl = '../backend/profilebackend.php';
    xhr.open('POST', fullurl, true);
    xhr.onload = function() {
      if (this.status == 200) {
        console.log(this.responseText);


        //ADDS 1 LIKE TO THE POST
        if (this.responseText == "successfully liked post") {
          document.getElementById("likes_" + post_id).innerHTML -= -1;
          document.getElementById("like_btn_" + post_id).style.color = "#0984E3";
        }
        //ADDS 1 DISLIKE TO THE POST
        if (this.responseText == "successfully disliked post") {
          document.getElementById("dislike_" + post_id).innerHTML -= -1;
          document.getElementById("dislike_btn_" + post_id).style.color = "#0984E3";
        }



      }
    }
    xhr.send(fd);




  }

  function setprofilepic() {

    var fd = new FormData();
    fd.append("setprofilepic", true);
    var xhr = new XMLHttpRequest();
    var fullurl = '../backend/profilebackend.php';
    xhr.open('POST', fullurl, true);
    xhr.onload = function() {
      if (this.status == 200) {

        console.log(this.responseText);

        document.getElementById("profilepic").setAttribute("style", "background-image:url('" + this.responseText + "');");

      }
    }
    xhr.send(fd);

  }



  function setuserbio() {

    var fd = new FormData();
    fd.append("setuserbio", true);
    var xhr = new XMLHttpRequest();
    var fullurl = '../backend/profilebackend.php';
    xhr.open('POST', fullurl, true);
    xhr.onload = function() {
      if (this.status == 200) {

        console.log(this.responseText);

        document.getElementById("bio-text").innerHTML = this.responseText;

      }
    }
    xhr.send(fd);

  }



  function findusername() {

    var fd = new FormData();
    fd.append("setuserusername", true);
    var xhr = new XMLHttpRequest();
    var fullurl = '../backend/profilebackend.php';
    xhr.open('POST', fullurl, true);
    xhr.onload = function() {
      if (this.status == 200) {

        console.log(this.responseText);

        document.getElementById("userusername").innerHTML = this.responseText;

      }
    }
    xhr.send(fd);

  }

  function findmoreofmyposts() {
    sortbystatus = document.getElementById('sortByStatus').innerHTML;
    last_post_id = document.getElementsByClassName('post-div')[document.getElementsByClassName('post-div').length - 1].id;
    scrollTop = document.getElementById('mainpage').scrollTop;

    if (sortbystatus == "newest") {

      var fd = new FormData();
      fd.append("last_post_id", last_post_id);
      fd.append("load_more_posts", true);
      fd.append("newest", true);
      var xhr = new XMLHttpRequest();
      var fullurl = '../backend/profilebackend.php';
      xhr.open('POST', fullurl, true);
      xhr.onload = function() {
        if (this.status == 200) {
					sortbystatus.innerHTML = "newest";
          console.log(this.responseText);
          place = document.getElementById('post-div');
          //PARSING THE ARRAY RETURNED BY THE BACKEND
          bigarray = JSON.parse(this.responseText);
          echo_posts(bigarray, place);
          document.getElementById('mainpage').scrollTop = scrollTop;
        }
      }
      xhr.send(fd);

    }


    if (sortbystatus == "oldest") {

      var fd = new FormData();
      fd.append("last_post_id", last_post_id);
      fd.append("load_more_posts", true);
      fd.append("oldest", true);
      var xhr = new XMLHttpRequest();
      var fullurl = '../backend/profilebackend.php';
      xhr.open('POST', fullurl, true);
      xhr.onload = function() {
        if (this.status == 200) {
					sortbystatus.innerHTML = "oldest";
          console.log(this.responseText);
          place = document.getElementById('post-div');
          //PARSING THE ARRAY RETURNED BY THE BACKEND
          bigarray = JSON.parse(this.responseText);
          echo_posts(bigarray, place);
          document.getElementById('mainpage').scrollTop = scrollTop;
        }
      }
      xhr.send(fd);

    }
    if (sortbystatus == "community") {

      var fd = new FormData();
      fd.append("last_post_id", last_post_id);
      fd.append("load_more_posts", true);
      fd.append("community", true);
      var xhr = new XMLHttpRequest();
      var fullurl = '../backend/profilebackend.php';
      xhr.open('POST', fullurl, true);
      xhr.onload = function() {
        if (this.status == 200) {
					sortbystatus.innerHTML = "community";
      //REMOVES THE POSTS THAT WERE THERE
      place = document.getElementById('post-div');

      //PARSES THE INCOMING ARRAY
      console.log(this.responseText);
      bigarray = JSON.parse(this.responseText);
      echo_posts_sortbycommunity(bigarray, place);
      document.getElementById('mainpage').scrollTop = scrollTop;
    }
  }
  xhr.send(fd);

    }

  }

  function updateprofilepic() {
    var allowedExtensions =
                      /(\.jpg|\.jpeg|\.png|\.JPG|\.JPEG|\.PNG)$/i;


    x = document.getElementById('file');
    file = x.files[0];
    fsize = file.size;
    filePath = document.getElementById("file").value;
    console.log(filePath);
    var filesize = Math.round((fsize/1000));
    if (filesize > 20000) {
      alert("This file is too big, it must be 20MB or less to be posted");
    } else if (!allowedExtensions.exec(filePath)) {
      alert("This file is of the wrong exetension, please send a .jpg, .jpeg, .png, .mp4, .mov");
    } else {
      var fd = new FormData();
      fd.append("file", file);
      fd.append("newprofilepic", true);
      var xhr = new XMLHttpRequest();
      var fullurl = '../backend/profilebackend.php';
      xhr.open('POST', fullurl, true);
      xhr.onload = function() {
        if (this.status == 200) {
          console.log(this.responseText);
          setuseravatar();
          setprofilepic();
        };
    };
    xhr.send(fd);







  }
}

function check() {
  document.querySelector('#file').removeEventListener('change', Myhandlerfunction, true);
  document.querySelector('#file').addEventListener('change', Myhandlerfunction , true);
}


function updatebio() {
  var newbio = prompt("Please enter your new bio!");

  var fd = new FormData();
  fd.append("newbio", newbio);
  fd.append("setnewbio", true);
  var xhr = new XMLHttpRequest();
  var fullurl = '../backend/profilebackend.php';
  xhr.open('POST', fullurl, true);
  xhr.onload = function() {
    if (this.status == 200) {
      console.log(this.responseText);
      setuserbio();
    };
};
xhr.send(fd);
}


function profile() {
  window.location.href = "../frontend/profile.php";
}


//FUNCTION FINDS THE FIRST 20 POSTS OR THE LAST 20 POSTS
function sortBy(e) {

  place = document.getElementById('post-div');


   if (e == "newest") {

    var fd = new FormData();
    fd.append("sorting", true);
    fd.append("newest", true);
    var xhr = new XMLHttpRequest();
    var fullurl = '../backend/profilebackend.php';
    xhr.open('POST', fullurl, true);
    xhr.onload = function() {
      if (this.status == 200) {
        //SETS THE SORT BY STATUS TO NEWEST
       document.getElementById('sortByStatus').innerHTML = "newest";

       console.log(this.responseText);
       //REMOVES THE POSTS THAT WERE THERE

			 i = document.getElementsByClassName("post-div");

			 while (i[0]) {
			 i[0].parentNode.removeChild(i[0]);
		 };
     bigarray = JSON.parse(this.responseText);
     echo_posts(bigarray, place);

      }
    }
    xhr.send(fd);

  } else if (e == "oldest") {

    var fd = new FormData();
    fd.append("sorting", true);
    fd.append("oldest", true);
    var xhr = new XMLHttpRequest();
    var fullurl = '../backend/profilebackend.php';
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
        echo_posts(bigarray, place);
      }
    }
    xhr.send(fd);

  } else if (e == "community") {

    var fd = new FormData();
    fd.append("sorting", true);
    fd.append("community", true);
    var xhr = new XMLHttpRequest();
    var fullurl = '../backend/profilebackend.php';
    xhr.open('POST', fullurl, true);
    xhr.onload = function() {
      if (this.status == 200) {
        //SETS THE SORT BY STATUS TO OLDEST
        document.getElementById('sortByStatus').innerHTML = "commmunity";

        //REMOVES THE POSTS THAT WERE THERE

				i = document.getElementsByClassName("post-div");

				while (i[0]) {
				i[0].parentNode.removeChild(i[0]);
			};

        //PARSES THE INCOMING ARRAY
        console.log(this.responseText);
        bigarray = JSON.parse(this.responseText);
        echo_posts_sortbycommunity(bigarray, place);
      }
    }
    xhr.send(fd);

  }
};


function echo_posts_sortbycommunity(bigarray, place) {



    for (i in bigarray) {

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
      var community_id = bigarray[i]["community_id"];
      var community_name = bigarray[i]["community_name"];

      if (document.getElementById(community_name) == null) {

        postcommunitydiv = document.createElement("div");
        postcommunitydiv.id = community_name;

        postcommunitytitle = document.createElement("p");
        postcommunitytitle.classList.add("postcommunitytitle");
        postcommunitytitle_text = document.createTextNode(community_name);
        postcommunitytitle.appendChild(postcommunitytitle_text);

        postcommunitydiv.appendChild(postcommunitytitle);

        offset = moment().utcOffset();
        d = moment(date_time).add(offset, 'minutes');
        difference = moment(d, "YYYY-MM-DD HH:mm:ss").fromNow();




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

        postcommunitydiv.appendChild(post_div);

        place.appendChild(postcommunitydiv);











      } else if (document.getElementById(community_name) != null) {

        post_community_div = document.getElementById(community_name);
        offset = moment().utcOffset();
        d = moment(date_time).add(offset, 'minutes');
        difference = moment(d, "YYYY-MM-DD HH:mm:ss").fromNow();




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

        post_community_div.appendChild(post_div);

        place.appendChild(post_community_div);



      }

    }

}
