function validateAddAdmin() {
    var firstname = document.forms["addadminform"]["firstname"].value;
    var lastname = document.forms["addadminform"]["lastname"].value;
    var username = document.forms["addadminform"]["username"].value;
    var email = document.forms["addadminform"]["email"].value;
    var password = document.forms["addadminform"]["password"].value;

    if (firstname == "" || lastname == "" || username == "" || email == "" || password == "") {
        alert("Please fill in all fields");
        return false;
    }
    if (password.length < 6) {
        alert("Password must be at least 6 characters");
        return false;
    }
    return true;
}