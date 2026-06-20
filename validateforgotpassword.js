function validateFindAccount() {
    var username = document.forms["findaccountform"]["username"].value;

    if(username.trim() == "") {
        alert("Please enter your username or email");
        return false;
    }

    return true;
}