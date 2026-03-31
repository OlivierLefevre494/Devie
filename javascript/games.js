window.onload = function run_on_load() {
	setuseravatar();
	browsegames();
	yo();
	autocomplete(document.getElementById("myInput"), countries);
}

function autocomplete(inp, arr) {
  /*the autocomplete function takes two arguments,
  the text field element and an array of possible autocompleted values:*/
  var currentFocus;
  /*execute a function when someone writes in the text field:*/
  inp.addEventListener("input", function(e) {
      var a, b, i, val = this.value;
      /*close any already open lists of autocompleted values*/
      closeAllLists();
      if (!val) { return false;}
      currentFocus = -1;
      /*create a DIV element that will contain the items (values):*/
      a = document.createElement("DIV");
      a.setAttribute("id", this.id + "autocomplete-list");
      a.setAttribute("class", "autocomplete-items");
      /*append the DIV element as a child of the autocomplete container:*/
      document.getElementById('tagcontainerdisplay').appendChild(a);
      /*for each item in the array...*/
      for (i = 0; i < arr.length; i++) {
        /*check if the item starts with the same letters as the text field value:*/
        if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
          /*create a DIV element for each matching element:*/
          b = document.createElement("DIV");
					b.setAttribute("class", "autocompleteitemdiv");

          /*make the matching letters bold:*/
          b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
          b.innerHTML += arr[i].substr(val.length);
          /*insert a input field that will hold the current array item's value:*/
          b.innerHTML += "<input type='hidden' class='hiddentagvalue' value='" + arr[i] + "'>";
          /*execute a function when someone clicks on the item value (DIV element):*/
          b.addEventListener("click", function(e) {
              /*insert the value for the autocomplete text field:*/
              inp.value = this.getElementsByTagName("input")[0].value;
              /*close the list of autocompleted values,
              (or any other open lists of autocompleted values:*/
              closeAllLists();
          });
          a.appendChild(b);
        }
      }
  });

  /*execute a function presses a key on the keyboard:*/
  inp.addEventListener("keydown", function(e) {
      var x = document.getElementById(this.id + "autocomplete-list");
      if (x) x = x.getElementsByTagName("div");
      if (e.keyCode == 40) {
        /*If the arrow DOWN key is pressed,
        increase the currentFocus variable:*/
        currentFocus++;
        /*and and make the current item more visible:*/
        addActive(x);
      } else if (e.keyCode == 38) { //up
        /*If the arrow UP key is pressed,
        decrease the currentFocus variable:*/
        currentFocus--;
        /*and and make the current item more visible:*/
        addActive(x);
      } else if (e.keyCode == 13) {
        /*If the ENTER key is pressed, prevent the form from being submitted,*/
        e.preventDefault();
        if (currentFocus > -1) {
          /*and simulate a click on the "active" item:*/
          if (x) x[currentFocus].click();
        }
      }
  });
  function addActive(x) {
    /*a function to classify an item as "active":*/
    if (!x) return false;
    /*start by removing the "active" class on all items:*/
    removeActive(x);
    if (currentFocus >= x.length) currentFocus = 0;
    if (currentFocus < 0) currentFocus = (x.length - 1);
    /*add class "autocomplete-active":*/
    x[currentFocus].classList.add("autocomplete-active");
  }
  function removeActive(x) {
    /*a function to remove the "active" class from all autocomplete items:*/
    for (var i = 0; i < x.length; i++) {
      x[i].classList.remove("autocomplete-active");
    }
  }
  function closeAllLists(elmnt) {
    /*close all autocomplete lists in the document,
    except the one passed as an argument:*/
    var x = document.getElementsByClassName("autocomplete-items");
    for (var i = 0; i < x.length; i++) {
      if (elmnt != x[i] && elmnt != inp) {
        x[i].parentNode.removeChild(x[i]);
      }
    }
  }
  /*execute a function when someone clicks in the document:*/
  document.addEventListener("click", function (e) {
      closeAllLists(e.target);
  });
}

/*An array containing all the country names in the world:*/
var countries = ["action", "rogue-lite", "simulator", "shooting", "platformer", "tower-defense", "idle", "adventure"];

function detect_search(e) {
	if((e && e.keyCode == 13) || e == 0) {
		var searchText = document.getElementById("search-space").value;

	}
}



function yo() {
tagContainer = document.querySelector('.tag-container');
input = document.querySelector('.tag-container input');

let tags = [];

function createTag(label) {
  const div = document.createElement('div');
  div.setAttribute('class', 'tag');
  const span = document.createElement('span');
	span.setAttribute("class", 'selectedtag');
  span.innerHTML = label;
  const closeIcon = document.createElement('i');
  closeIcon.setAttribute('data-item', label);
	closeIcon.setAttribute('class', 'fas fa-times');
  div.appendChild(span);
  div.appendChild(closeIcon);
  return div;
}

function clearTags() {
  document.querySelectorAll('.tag').forEach(tag => {
    tag.parentElement.removeChild(tag);
  });
}

function addTags() {
  clearTags();
  tags.slice().reverse().forEach(tag => {
    tagContainer.prepend(createTag(tag));
  });
}

document.querySelector('.tag-container input').addEventListener('keyup', (e) => {
    if (e.key === 'Enter') {
			inp = document.getElementById("myInput");
      e.target.value.split(',').forEach(tag => {
        tags.push(tag);
      });
			elmnt = "";
			var x = document.getElementsByClassName("autocomplete-items");
			for (var i = 0; i < x.length; i++) {
				if (elmnt != x[i] && elmnt != inp) {
					x[i].parentNode.removeChild(x[i]);
				}
			}
      addTags();
      input.value = '';
    }
});
document.addEventListener('click', (e) => {
	if (e.target.classList == 'autocompleteitemdiv') {
		e.target.querySelector(".hiddentagvalue").value.split(',').forEach(tag => {
			tags.push(tag);
		});
		elmnt = "";
		var x = document.getElementsByClassName("autocomplete-items");
		for (var i = 0; i < x.length; i++) {
			if (elmnt != x[i] && elmnt != inp) {
				x[i].parentNode.removeChild(x[i]);
			}
		}
		addTags();
		input.value = '';

	}
})



document.addEventListener('click', (e) => {
  console.log(e.target.tagName);
  if (e.target.tagName === 'I') {
    const tagLabel = e.target.getAttribute('data-item');
    const index = tags.indexOf(tagLabel);
    tags = [...tags.slice(0, index), ...tags.slice(index+1)];
    addTags();
  }
})

input.focus();
}

function friends() {
	window.location.href = "../frontend/friends.php";
}

function detect_search(e) {
	if((e && e.keyCode == 13) || e == 0) {
		var searchText = document.getElementById("search-space").value;

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
      findmoregames();
      }
    if ((mainpage.scrollTop + mainpage.offsetHeight) >= 800) {
      x = document.getElementById('return-to-top');
      x.style.display = "block";
    }
  }



	function findmoregames() {

		lastgameid = document.getElementsByClassName('game-result-div')[document.getElementsByClassName('game-result-div').length - 1].id;

		var fd = new FormData();
		fd.append("lastgameid", lastgameid);
		fd.append("findmoregames", true);
		var xhr = new XMLHttpRequest();
		var fullurl = '../backend/gamesearch.php';
		xhr.open('POST', fullurl, true);
		xhr.onload = function() {
			if (this.status == 200) {

				console.log(this.responseText);

				//DELETE WHATEVER WAS WHERE I AM NOW GOING TO PUT THE BROWSE RESULTS
				searchresults = document.getElementById("searchresults");
        //PARSES THE RESULTS
        bigarray = JSON.parse(this.responseText);

				displaygames(bigarray, searchresults);
			};
	};
	xhr.send(fd);

	}


	function browsegames() {



		var fd = new FormData();
		fd.append("browsegames", true);
		var xhr = new XMLHttpRequest();
		var fullurl = '../backend/gamesearch.php';
		xhr.open('POST', fullurl, true);
		xhr.onload = function() {
			if (this.status == 200) {

				console.log(this.responseText);

				//DELETE WHATEVER WAS WHERE I AM NOW GOING TO PUT THE BROWSE RESULTS
				searchresults = document.getElementById("searchresults");
        searchresults.innerHTML = "";
        //PARSES THE RESULTS
        bigarray = JSON.parse(this.responseText);

				displaygames(bigarray, searchresults);
			};
	};
	xhr.send(fd);




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
		    game_result_div.classList.add("game-result-div");
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

function selecttag(tagid) {

	tagid.setAttribute("style","background-color: #34ce34;");
	tagid.setAttribute("onclick","deselecttag(" + tagid.id + ")");
	tagid.setAttribute("class","selectedtag");


}

function deselecttag(tagid) {
	tagid.setAttribute("style","background-color: white;");
	tagid.setAttribute("onclick","selecttag(" + tagid.id + ")");
	tagid.setAttribute("class","");
}

function gamesearch() {

	var text_search = document.getElementById("search-space").value;
	var creator_search = document.getElementById("search-space-creator").value;
	var tags = document.getElementsByClassName("selectedtag");

	var tagarray = [];

	for (i in tags) {
	if (tags[i].innerHTML != undefined) {
		 tagarray.push(tags[i].innerHTML);
	}
}

	console.log(tagarray);
	var fd = new FormData();
	fd.append("text_search", text_search);
	fd.append("creator_search", creator_search);
	fd.append("tagarray", JSON.stringify(tagarray));
	fd.append("gamesearch", true);
	var xhr = new XMLHttpRequest();
	var fullurl = '../backend/gamesearch.php';
	xhr.open('POST', fullurl, true);
	xhr.onload = function() {
		if (this.status == 200) {

			searchresults = document.getElementById("searchresults");
				searchresults.innerHTML = "";
				//PARSES THE RESULTS
				bigarray = JSON.parse(this.responseText);

				displaygames(bigarray, searchresults);

		};
	};
		xhr.send(fd);

}

function profile() {
  window.location.href = "../frontend/profile.php";
}
