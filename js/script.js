const urlBase = 'http://pastelparadise.xyz/LAMPAPI';
const extension = 'php';

let userId = 0;
let firstName = "";
let lastName = "";

function doLogin() {
  userId = 0;
  firstName = "";
  lastName = "";

  let login = document.getElementById("loginName").value;
  let password = document.getElementById("loginPassword").value;
  //	var hash = md5( password );

  document.getElementById("loginResult").innerHTML = "";
  //alert(login + " " + password);
  console.log(login);
  let tmp = { login: login, password: password };
  //	var tmp = {login:login,password:hash};
  let jsonPayload = JSON.stringify(tmp);

  let url = urlBase + '/Login.' + extension;

  let xhr = new XMLHttpRequest();
  xhr.open("POST", url, true);
  xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
  try {
    xhr.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        let jsonObject = JSON.parse(xhr.responseText);
        userId = jsonObject.id;

        if (userId < 1) {
          document.getElementById("loginResult").innerHTML = "<br><img src='images/errorprompt.png' id='errorIcon'>  User/Password combination incorrect";
          return;
        }

        firstName = jsonObject.firstName;
        lastName = jsonObject.lastName;

        saveCookie();

        window.location.href = "contacts.html";	//user exists so now we go to colors page
      }
    };
    xhr.send(jsonPayload);
  }
  catch (err) {
    document.getElementById("loginResult").innerHTML = "</br>" + err.message;
  }

}

function doSignup() {
  document.getElementById("signupResult").innerHTML = "";

  let firstName = document.getElementById("first").value;
  let lastName = document.getElementById("last").value;
  let username = document.getElementById("user").value;
  let password = document.getElementById("pass").value;
  let error = 0;

  if (firstName.length < 1) {
    error += 1;
    document.getElementById("signupResult").innerHTML += "<br>"
    document.getElementById("signupResult").innerHTML += "<img src='images/errorprompt.png' id='errorIcon'>";
    document.getElementById("signupResult").innerHTML += "  Please input a first name.";
  }

  if (lastName.length < 1) {
    document.getElementById("signupResult").innerHTML += "<br>";
    document.getElementById("signupResult").innerHTML += "<img src='images/errorprompt.png' id='errorIcon'>";
    document.getElementById("signupResult").innerHTML += "  Please input a last name.";
    error += 1;
  }

  if (username.length < 3) {
    document.getElementById("signupResult").innerHTML += "<br>";
    document.getElementById("signupResult").innerHTML += "<img src='images/errorprompt.png' id='errorIcon'>";
    document.getElementById("signupResult").innerHTML += "  Username must be at least 3 characters long";
    error += 1;
  }

  if (password.length < 8) {
    document.getElementById("signupResult").innerHTML += "<br>";
    document.getElementById("signupResult").innerHTML += "<img src='images/errorprompt.png' id='errorIcon'>";
    document.getElementById("signupResult").innerHTML += "  Password must be at least 8 characters long";
    error += 1;
  }

  if (error > 0) {
    return;
  }

  //let xhr = new XMLHttpRequest();
  //xhr.open("POST", url, true);
  //xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
  //just added this! still needs work- i will continue in the am! -Tabby
  let tmp = {
    firstName: firstName,
    lastName: lastName,
    login: username,
    password: password
  };

  let jsonPayload = JSON.stringify(tmp);

  let url = urlBase + '/register.' + extension;

  let xhr = new XMLHttpRequest();
  xhr.open("POST", url, true);
  xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");

  try {
    xhr.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        let jsonObject = JSON.parse(xhr.responseText);
        userId = jsonObject.id;
        //


        firstName = jsonObject.firstName;
        lastName = jsonObject.lastName;
        //firstName = jsonObject.first_name;
        //lastName = jsonObject.last_name;

        saveCookie();

        window.location.href = "contacts.html";
      }
    };
    xhr.send(jsonPayload);
  }
  catch (err) {
    document.getElementById("signupResult").innerHTML = "<br>" + err.message;
    // the break is what makes the message go under the button until next to it
    // sorry for disappearing the whole day i panicked
    // - sara
    return;
  }
  document.getElementById("signupResult").innerHTML = "User added successfully!";
}

function saveCookie() {
  let minutes = 20;
  let date = new Date();
  date.setTime(date.getTime() + (minutes * 60 * 1000));
  document.cookie = "firstName=" + firstName + ",lastName=" + lastName + ",userId=" + userId + ";expires=" + date.toGMTString();
}

function readCookie() {
  userId = -1;
  let data = document.cookie;
  let splits = data.split(",");
  for (var i = 0; i < splits.length; i++) {
    let thisOne = splits[i].trim();
    let tokens = thisOne.split("=");
    if (tokens[0] == "firstName") {
      firstName = tokens[1];
    }
    else if (tokens[0] == "lastName") {
      lastName = tokens[1];
    }
    else if (tokens[0] == "userId") {
      userId = parseInt(tokens[1].trim());
    }
  }

  if (userId < 0) {
    window.location.href = "index.html";
  }
  else {
    //		document.getElementById("userName").innerHTML = "Logged in as " + firstName + " " + lastName;
  }
}

function doLogout() {
  userId = 0;
  firstName = "";
  lastName = "";
  document.cookie = "firstName= ; expires = Thu, 01 Jan 1970 00:00:00 GMT";
  window.location.href = "index.html";
}

function addContact() {
  let newFirstName = document.getElementById("fname").value;
  let newLastName = document.getElementById("lname").value;
  let newEmail = document.getElementById("email").value;
  let newPhoneNo = document.getElementById("phone").value;

  document.getElementById("contactAddResult").innerHTML = "";

  let tmp = {
    firstName: newFirstName,
    lastName: newLastName,
    email: newEmail,
    phone: newPhoneNo,
    userId: userId
  };

  let jsonPayload = JSON.stringify(tmp);

  let url = urlBase + '/Create_Contact.' + extension;

  let xhr = new XMLHttpRequest();
  xhr.open("POST", url, true);
  xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
  try {
    xhr.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("contactAddResult").innerHTML = "Contact added successfully";
        document.getElementById("contactForm").reset();
        loadContacts(); // TODO: create loadcontacts 
        showTable(); // TODO: create showtable
      }
    };
    xhr.send(jsonPayload);
  }
  catch (err) {
    document.getElementById("contactAddResult").innerHTML = err.message;
    return;
  }

}

function loadContacts() {
  let tmp = {
    search: "",
    userId: userId
  };

  let jsonPayload = JSON.stringify(tmp);

  let url = urlBase + '/SearchContacts.' + extension;
  let xhr = new XMLHttpRequest();
  xhr.open("POST", url, true);
  xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");

  try {
    xhr.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        let jsonObject = JSON.parse(xhr.responseText);
        if (jsonObject.error) {
          console.log(jsonObject.error);
          return;
        }

        let table = document.getElementById("contactsTable").innerHTML;

        for (let i = 0; i < jsonObject.results.length; i++) {
          table += "<tr id='row" + i + "'>"
          table += "<td id='fname" + i + "'>" + jsonObject.results[i].firstName + "</td>"
          table += "<td id='lname" + i + "'>" + jsonObject.results[i].lastName + "</td>"
          table += "<td id='email" + i + "'>" + jsonObject.results[i].email + "</td>"
          table += "<td id='phone" + i + "'>" + jsonObject.results[i].phone + "</td>"
          table += "<td id='actions" + i + "'>todo</td>"
          table += "</tr>";
        }
        table += "<tr>"
        table += "<td><p>" + jsonObject.results.length + "</p></td>";
        table += "</tr>"
      }
    };
    xhr.send(jsonPayload);

  } catch (err) {
    console.log(jsonObject.error);
    return;
  }
}

function searchContacts() {
  let srch = document.getElementById("searchText").value;
  document.getElementById("colorSearchResult").innerHTML = "";

  let colorList = "";

  let tmp = { search: srch, userId: userId };
  let jsonPayload = JSON.stringify(tmp);

  let url = urlBase + '/SearchContacts.' + extension;

  let xhr = new XMLHttpRequest();
  xhr.open("POST", url, true);
  xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
  try {
    xhr.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("colorSearchResult").innerHTML = "Color(s) has been retrieved";
        let jsonObject = JSON.parse(xhr.responseText);

        for (let i = 0; i < jsonObject.results.length; i++) {
          colorList += jsonObject.results[i];
          if (i < jsonObject.results.length - 1) {
            colorList += "<br />\r\n";
          }
        }

        document.getElementsByTagName("p")[0].innerHTML = colorList;
      }
    };
    xhr.send(jsonPayload);
  }
  catch (err) {
    document.getElementById("colorSearchResult").innerHTML = err.message;
  }

}
