window.onload = function run_on_load() {
  setuseravatar();
  playgame();
  setsidegames();
}

function friends() {
  window.location.href = "../frontend/friends.php";
}


function resizeIFrameToFitContent( iFrame_id ) {
  iFrame = document.getElementById(iFrame_id);

  try {
    iFrame = document.getElementById(iFrame_id);
    iFrame.width = iFrame.contentWindow.document.body.scrollWidth;
    iFrame.height = iFrame.contentWindow.document.body.scrollHeight;
  } catch (e) {
    iFrame = document.getElementById(iFrame_id);
    iFrame.width = "100%";
    iFrame.height = "100%";
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


//FUNCTION SETS THE USER'S AVATAR IN THE UI
function setuseravatar() {
  var fd = new FormData();
  fd.append("useravatar", true);
  var xhr = new XMLHttpRequest();
  var fullurl = '../backend/communitysearch.php';
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
      recommendmoregames();
      }
    if ((mainpage.scrollTop + mainpage.offsetHeight) >= 800) {
      x = document.getElementById('return-to-top');
      x.style.display = "block";
    }
  }


function playgame() {

  game_id = document.getElementById('game_id').innerHTML;

  var fd = new FormData();
  fd.append("game_id", game_id);
  fd.append("playgame", true);
  var xhr = new XMLHttpRequest();
  var fullurl = '../backend/displaygamesbackend.php';
  xhr.open('POST', fullurl, true);
  xhr.onload = function() {
    if (this.status == 200) {
      console.log(this.responseText);

      searchresults = document.getElementById("searchresults");
      //PARSES THE RESULTS
      bigarray = JSON.parse(this.responseText);
      //DISPLAYS THE RESULTS
      displaygameiframe(bigarray, searchresults);


    };
};
xhr.send(fd);
}

function openFullscreen() {
  elem = document.getElementById("game_iframe_div");
  if (elem.requestFullscreen) {
    elem.requestFullscreen();
  } else if (elem.webkitRequestFullscreen) { /* Safari */
    elem.webkitRequestFullscreen();
  } else if (elem.msRequestFullscreen) { /* IE11 */
    elem.msRequestFullscreen();
  }
}


function displaygameiframe(bigarray, searchresults) {

  for (i in bigarray) {

    //EXTRACTS INFO FROM ARRAY
    var game_location = bigarray[i]["location"];
    var game_title = bigarray[i]["name"];
    var credits = bigarray[i]["credits"];
    var description = bigarray[i]["description"];
    var controls = bigarray[i]["controls"];

    //CREATES A DIV TO PUT THE SEARCH RESULT INTO
    game_result_div = document.createElement("div");
    game_result_div.classList.add("game-result-div");

    textdiv = document.createElement("div");
    textdiv.classList.add("textdiv");

    //CREATES THE GAME I FRAME

    game_iframe_div = document.createElement("div");
    game_iframe_div.classList.add("game-iframe-div");
    game_iframe_div.id = "game_iframe_div";


    game_iframe = document.createElement("iframe");
    game_iframe.classList.add("game-iframe");
    game_iframe.id = "game_iframe";
    game_iframe.setAttribute("src", game_location);
    game_iframe.setAttribute("onload", "resizeIFrameToFitContent('game_iframe')");
    //ADDS IT TO THE GAME IFRAME DIV

    game_iframe_div.appendChild(game_iframe);

    game_result_div.appendChild(game_iframe_div);

    //CREATES THE FULLSCREEN DIV
    fullscreen_div = document.createElement("div");
    fullscreen_div.classList.add("fullscreen-div");
    fullscreen_btn = document.createElement("i");
    fullscreen_btn.classList.add("fas");
    fullscreen_btn.classList.add("fa-expand");
    fullscreen_btn.classList.add("fa-2x");
    fullscreen_btn.setAttribute("onclick", "openFullscreen()");
    fullscreen_btn.classList.add("fullscreen-btn");
    fullscreen_div.appendChild(fullscreen_btn);

    //ADDS IT TO THE GAME IFRAME DIV
    game_result_div.appendChild(fullscreen_div);

    //CREATES THE GAME TITLE
    gametitle = document.createElement("p");
    gametitle.classList.add("game-title");
    gametitletext = document.createTextNode(game_title);
    gametitle.appendChild(gametitletext);
    //ADDS IT TO THE GAME IFRAME DIV
    textdiv.appendChild(gametitle);

    gamedescriptiontitle = document.createElement("p");
    gamedescriptiontitle.classList.add("game-description-title");
    gamedescriptiontitletext = document.createTextNode("Game descritption:");
    gamedescriptiontitle.appendChild(gamedescriptiontitletext);
    //ADDS IT TO THE GAME IFRAME DIV
    textdiv.appendChild(gamedescriptiontitle);

    //CREATES THE GAME DESCRIPTION
    gamedescription = document.createElement("p");
    gamedescription.classList.add("game-description");
    gamedescriptiontext = document.createTextNode(description);
    gamedescription.appendChild(gamedescriptiontext);
    //ADDS IT TO THE GAME IFRAME DIV
    textdiv.appendChild(gamedescription);

    gamecreditstitle = document.createElement("p");
    gamecreditstitle.classList.add("game-credits-title");
    gamecreditstitletext = document.createTextNode("Game credits:");
    gamecreditstitle.appendChild(gamecreditstitletext);
    //ADDS IT TO THE GAME IFRAME DIV
    textdiv.appendChild(gamecreditstitle);

    //CREATE THE GAME CREDITS
    gamecredits = document.createElement("p");
    gamecredits.classList.add("game-credits");
    gamecreditstext = document.createTextNode(credits);
    gamecredits.appendChild(gamecreditstext);
    //ADDS IT TO THE GAME IFRAME DIV
    textdiv.appendChild(gamecredits);


    gamecontrolstitle = document.createElement("p");
    gamecontrolstitle.classList.add("game-controls-title");
    gamecontrolstitletext = document.createTextNode("Game controls:");
    gamecontrolstitle.appendChild(gamecontrolstitletext);
    //ADDS IT TO THE GAME IFRAME DIV
    textdiv.appendChild(gamecontrolstitle);


    //CREATE THE GAME CONTROLS
    gamecontrols = document.createElement("p");
    gamecontrols.classList.add("game-controls");
    gamecontrolstext = document.createTextNode(controls);
    gamecontrols.appendChild(gamecontrolstext);
    //ADDS IT TO THE GAME IFRAME DIV
    textdiv.appendChild(gamecontrols);

    game_result_div.appendChild(textdiv);

    searchresults.appendChild(game_result_div);
  }
}




function displaygames(bigarray, searchresults) {

    for (i in bigarray) {

      //EXTRACTS INFO FROM ARRAY
      var game_id = bigarray[i]["id"];
      var game_title = bigarray[i]["name"];
      var cover = bigarray[i]["cover"];
      var creator = bigarray[i]["creator"];
      var tags = bigarray[i]["tags"];

      //CREATES A DIV TO PUT THE SEARCH RESULT INTO
      game_result_div = document.createElement("div");
      game_result_div.classList.add("game-result-div-side");
      game_result_div.id = game_id;

      //CREATES THE GAME COVER IMAGE
      game_cover = document.createElement("img");
      game_cover.setAttribute("src", cover);
      game_cover.classList.add("game-cover");

      game_result_div.appendChild(game_cover);


      //CREATES THE GAME NAME
      game_name = document.createElement("p");
      game_name.classList.add("game-name");
      game_name_text = document.createTextNode(game_title);
      game_name.appendChild(game_name_text);

      game_result_div.appendChild(game_name);

      //CREATES THE CREDITS, BY : AUTHOR
      credits = document.createElement("p");
      credits.classList.add("creator-name");
      credits_text = document.createTextNode("By: " + creator);
      credits.appendChild(credits_text);

      game_result_div.appendChild(credits);

      //CREATES THE PLAY BUTTON
      play_btn = document.createElement("button");
      play_btn.classList.add("play-button");
      play_btn.setAttribute("onclick","clickedgame(" + game_id + ")");
      //ADDS TEXT TO IT
      play_btn_text = document.createTextNode("Play");
      play_btn.appendChild(play_btn_text);

      game_result_div.appendChild(play_btn);


      tags_print = document.createElement("div");
      tags_print.classList.add("all-tags");

      tag = document.createElement("p");
      tag.classList.add("tag-name");
      tag_text_string = "Tags : ";


      var str = tags;
      var res = str.split(" ");
      for (i in res) {
        if (i == 0) {
          tag_text_string = tag_text_string + res[i];
        } else {
          tag_text_string = tag_text_string + ", " + res[i];
        }


      }

      tag_text = document.createTextNode(tag_text_string);
      tag.appendChild(tag_text);
      tags_print.appendChild(tag);
      game_result_div.appendChild(tags_print);

      searchresults.appendChild(game_result_div);
    }

}

function setsidegames() {

  game_id = document.getElementById('game_id').innerHTML;

  var fd = new FormData();

  fd.append("game_id", game_id);
	fd.append("sidegames", true);
	var xhr = new XMLHttpRequest();
	var fullurl = '../backend/displaygamesbackend.php';
	xhr.open('POST', fullurl, true);
	xhr.onload = function() {
		if (this.status == 200) {

      console.log(this.responseText);
      searchresults = document.getElementById("sidegames");
      bigarray = JSON.parse(this.responseText);

      displaygames(bigarray, searchresults);

    };
  };
    xhr.send(fd);
}


function clickedgame(game_id) {

	var fd = new FormData();
	fd.append("game_id", game_id)
	fd.append("clickedgame", true);
	var xhr = new XMLHttpRequest();
	var fullurl = '../backend/gamesearch.php';
	xhr.open('POST', fullurl, true);
	xhr.onload = function() {
		if (this.status == 200) {

			window.location.href='gamesdisplay.php?id=' +  game_id;

		};
		};
		xhr.send(fd);


}

function recommendmoregames() {
  game_id = document.getElementById('game_id').innerHTML;
  last_game_id = document.getElementsByClassName("game-result-div-side")[document.getElementsByClassName('game-result-div-side').length - 1].id

  var fd = new FormData();

  fd.append("game_id", game_id);
  fd.append("last_game_id", last_game_id);
	fd.append("recommendmoregames", true);
	var xhr = new XMLHttpRequest();
	var fullurl = '../backend/displaygamesbackend.php';
	xhr.open('POST', fullurl, true);
	xhr.onload = function() {
		if (this.status == 200) {

      console.log(this.responseText);
      searchresults = document.getElementById("sidegames");
      bigarray = JSON.parse(this.responseText);

      displaygames(bigarray, searchresults);

    };
  };
    xhr.send(fd);
}


function profile() {
  window.location.href = "../frontend/profile.php";
}
