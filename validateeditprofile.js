function validateEditProfile() {
    var firstname = document.forms["editprofileform"]["firstname"].value;
    var lastname = document.forms["editprofileform"]["lastname"].value;
    var username = document.forms["editprofileform"]["username"].value;
    var email = document.forms["editprofileform"]["email"].value;
    var phone = document.forms["editprofileform"]["phone"].value;
    var current_password = document.forms["editprofileform"]["current_password"].value;
    var new_password = document.forms["editprofileform"]["new_password"].value;
    var confirm_password = document.forms["editprofileform"]["confirm_password"].value;

    if (firstname == "" || lastname == "" || username == "" || email == "" || phone == "") {
        alert("Please fill in all required fields");
        return false;
    }

    // only validate password fields if admin is trying to change password
    if (new_password != "" || confirm_password != "") {
        if (current_password == "") {
            alert("Please enter your current password to change it");
            return false;
        }
        if (new_password.length < 6) {
            alert("New password must be at least 6 characters");
            return false;
        }
        if (new_password != confirm_password) {
            alert("New password and confirm password do not match");
            return false;
        }
    }

    return true;
}

function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('profilePicPreview').src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}