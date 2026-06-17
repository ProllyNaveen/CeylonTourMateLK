function validateLoginForm() {
    var username = document.forms["loginform"]["username"].value;
    var password = document.forms["loginform"]["password"].value;

    if(username.trim() == "") {
        alert("Please enter your username or email");
        return false;
    }
    if(password == "") {
        alert("Please enter your password");
        return false;
    }

    return true;
}