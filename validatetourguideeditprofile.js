function validateTourGuideEditProfile() {
    var firstname = document.forms["tourguideeditprofileform"]["firstname"].value;
    var lastname = document.forms["tourguideeditprofileform"]["lastname"].value;
    var username = document.forms["tourguideeditprofileform"]["username"].value;
    var email = document.forms["tourguideeditprofileform"]["email"].value;
    var phone = document.forms["tourguideeditprofileform"]["phone"].value;
    var current_password = document.forms["tourguideeditprofileform"]["current_password"].value;
    var new_password = document.forms["tourguideeditprofileform"]["new_password"].value;
    var confirm_password = document.forms["tourguideeditprofileform"]["confirm_password"].value;

    if (firstname == "" || lastname == "" || username == "" || email == "" || phone == "") {
        alert("Please fill in all required fields");
        return false;
    }
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