function validateResetPassword() {
    var newpassword = document.forms["resetform"]["new_password"].value;
    var confirmpassword = document.forms["resetform"]["confirm_password"].value;

    if(newpassword == "") {
        alert("Please enter a new password");
        return false;
    }
    if(newpassword.length < 6) {
        alert("Password must be at least 6 characters!");
        return false;
    }
    if(confirmpassword == "") {
        alert("Please confirm your new password");
        return false;
    }
    if(newpassword != confirmpassword) {
        alert("New password and Confirm password do not match");
        return false;
    }

    return true;
}