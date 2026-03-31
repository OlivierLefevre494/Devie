
  window.onload = function run_on_load() {
    browse_communities();
    setuseravatar();
  }

  function friends() {
    window.location.href = "../frontend/friends.php";
  }
//FUNCTION IS GIVEN A BIG ARRAY OF COMMUNITIES AND THE FUNCTION DISPLAYS THEM
function displaycommunities(bigarray, searchresults) {

  for (i in bigarray) {

    //EXTRACTS INFO FROM ARRAY
    var community_id = bigarray[i]["community_id"];
    var community_name = bigarray[i]["community_name"];
    var community_avatar = bigarray[i]["community_avatar"];
    var community_banner = bigarray[i]["community_banner"];
    var create_time = bigarray[i]["create_time"];

    offset = moment().utcOffset();
    d = moment(create_time).add(offset, 'minutes');
    difference = moment(d, "YYYY-MM-DD HH:mm:ss").fromNow();

    //CREATES A DIV TO PUT THE SEARCH RESULT INTO
    community_result_div = document.createElement("div");
    community_result_div.classList.add("community-result-div");

    community_info_div = document.createElement("div");
    community_info_div.classList.add("info-div");

    //CREATES THE COMMUNITY AVATAR
    community_avatar_div = document.createElement("div");
    community_avatar_div.classList.add("community-avatar");
    community_avatar_div.setAttribute("style","background-image: url('" + community_avatar + "')");
    //PUTS THE COMMUNITY AVATAR INTO THE DIV FOR THE RESULT
    community_result_div.appendChild(community_avatar_div);


    //CREATES THE LINK TO THE COMMUNITY
    community_display_link = document.createElement("a");
    community_display_link.classList.add("community-name");
    community_display_link.setAttribute("href","communitychatdisplay.php?id=" + community_id + "&id2=" + community_banner + "&avatar=" + community_avatar);
    community_display_link_text = document.createTextNode(community_name);
    community_display_link.appendChild(community_display_link_text);

    community_info_div.appendChild(community_display_link);

    numofmembers = document.createElement("p");
    numofmembers.classList.add("numofmembers");
    numofmembers.setAttribute("id",community_id);

    community_info_div.appendChild(numofmembers);

    creationdate = document.createElement("p");
    creationdate.classList.add("creationdate");
    creationdate_text = document.createTextNode("Created " + difference);
    creationdate.appendChild(creationdate_text);

    community_info_div.appendChild(creationdate);

    community_result_div.appendChild(community_info_div);

    searchresults.appendChild(community_result_div);
    numofmembersdisplay(community_id);
  }
}




function detect_search(e) {
  if((e && e.keyCode == 13) || e == 0) {
     //alert("Enter (13) detected!!");
     search  = document.getElementById('search-space').value;
     var data = new FormData();
     data.append('search',  search);
     data.append('findcommunities', true)
     var xhr = new XMLHttpRequest();
     var fullurl = '../backend/communitysearch.php';
     xhr.open('POST', fullurl, true);
     xhr.onload = function() {
       if (this.status == 200) {

         //THE ELEMENT TO PUT THE SEARCH RESULTS IN
         searchresults = document.getElementById("searchresults");
         searchresults.innerHTML = "";


         //IF THE QUERY RETURNED NO RESULTS
         if (this.responseText == "no communities with that name") {
           //CREATED A DIV FOR THE MESSAGE AND BUTTON TO BE PLACED IN
           create_community_div = document.createElement("div");
           create_community_div.classList.add("create-community-div");


           //CREATES THE TEXT MESSAGE TO THE USER
           no_communities_message = document.createElement("p");
           no_communities_message.classList.add("no-communities-message");
           no_communities_message_text = document.createTextNode("Oh no! We didn't find any communities with the name you search! Make sure you didn't misspell the title of the community you are searching for! If you didn't misspell the title of the community, that means we don't have a community with that name yet! Click the button below for our team to review this new community name. Check back in a few days to see if the community has been added!");
           no_communities_message.appendChild(no_communities_message_text);

           create_community_div.appendChild(no_communities_message);

           search  = document.getElementById('search-space').value;
           //CREATES THE BUTTON TO ADD A COMMUNITY
           create_community_button = document.createElement("button");
           create_community_button.classList.add("create-community-button");
           create_community_button.setAttribute("onclick","createcommunity('"+ search + "')");
           create_community_button_text = document.createTextNode("Click me!");
           create_community_button.appendChild(create_community_button_text);

           create_community_div.appendChild(create_community_button);

           searchresults.appendChild(create_community_div);

           document.getElementById('search-space').value='';
         } else {
           //IF THE QUERY RETURNED RESULTS

           //PARSES THE INCOMING ARRAY OF DATA
           bigarray = JSON.parse(this.responseText);

           displaycommunities(bigarray, searchresults)
           document.getElementById('search-space').value='';
         }


       };
     };
     xhr.send(data)
  }
}


//FUNCTION DISPLAYS SOME COMMUNITIES TO BROWSE
function browse_communities() {


  var data = new FormData();
  data.append('browse_communities', true)
  var xhr = new XMLHttpRequest();
  var fullurl = '../backend/communitysearch.php';
  xhr.open('POST', fullurl, true);
  xhr.onload = function() {
    if (this.status == 200) {
        //IF THE CODE WAS EXECUTED
        console.log(this.responseText);


        //DELETE WHATEVER WAS WHERE I AM NOW GOING TO PUT THE QUERY RESULTS
        searchresults = document.getElementById("searchresults");
        searchresults.innerHTML = "";
        //PARSES THE RESULTS
        bigarray = JSON.parse(this.responseText);
        //DISPLAYS THE RESULTS
        displaycommunities(bigarray, searchresults);



      };
    };
    xhr.send(data)







}


//FUNCTION THAT TELLS THE DEVS IF A COMMUNITY IS BEING SUGGESTED A LOT
function createcommunity(search) {
  if (search.length > 2000) {
    alert("Sorry, but the community name has to be less than 2000 characters!");
  }
  var data = new FormData();
  data.append('search', search)
  data.append('createcommunity', true)
  var xhr = new XMLHttpRequest();
  var fullurl = '../backend/communitysearch.php';
  xhr.open('POST', fullurl, true);
  xhr.onload = function() {
    if (this.status == 200) {

      if (this.responseText == "not logged in") {
        alert("You must be logged in to submit a community for review!");
      }
      if (this.responseText == "") {
        alert("Thanks for the suggestion! Check if the community has been added in a couple days!")
      }

    };
  };
  xhr.send(data)
}

//FUNCTION FINDS THE NUMBER OF MEMBERS IN A GIVEN COMMUNITY AND DISPLAYS IT INTO THE SEARCH RESULTS
function numofmembersdisplay(community_id) {
  var data = new FormData();
  data.append('community_id', community_id)
  data.append('numofmembers', true)
  var xhr = new XMLHttpRequest();
  var fullurl = '../backend/communitysearch.php';
  xhr.open('POST', fullurl, true);
  xhr.onload = function() {
    if (this.status == 200) {
      document.getElementById(community_id).innerHTML = this.responseText + " Members";
    };
  };
  xhr.send(data)

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
      browsemorecommunities()
      }
    if ((mainpage.scrollTop + mainpage.offsetHeight) >= 800) {
      x = document.getElementById('return-to-top');
      x.style.display = "block";
    }
  }



function browsemorecommunities() {

  var lastcommunityid = document.getElementsByClassName('community-result-div')[document.getElementsByClassName('community-result-div').length - 1];
  var id = lastcommunityid.getElementsByClassName('numofmembers');
  var id = id[0].id;

  var fd = new FormData();
  fd.append("lastcommunityid", id);
  fd.append("browsemorecommunities", true);
  var xhr = new XMLHttpRequest();
  var fullurl = '../backend/communitysearch.php';
  xhr.open('POST', fullurl, true);
  xhr.onload = function() {
    if (this.status == 200) {

      console.log(this.responseText);

      searchresults = document.getElementById("searchresults");
      //PARSES THE RESULTS
      bigarray = JSON.parse(this.responseText);
      //DISPLAYS THE RESULTS
      displaycommunities(bigarray, searchresults);

    };
};
xhr.send(fd);

}

function profile() {
  window.location.href = "../frontend/profile.php";
}
