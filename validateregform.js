function toggleRoleFields() {
    var role = document.getElementById("role").value;

    if(role == "tour_guide") {
        document.getElementById("tourguide-fields").style.display = "block";
    } else {
        document.getElementById("tourguide-fields").style.display = "none";
    }
}

function validateRegForm() {
    var role = document.forms["regform"]["role"].value;
    var firstname = document.forms["regform"]["firstname"].value;
    var lastname = document.forms["regform"]["lastname"].value;
    var username = document.forms["regform"]["username"].value;
    var email = document.forms["regform"]["email"].value;
    var phone = document.forms["regform"]["phone"].value;
    var password = document.forms["regform"]["password"].value;
    var cpassword = document.forms["regform"]["cpassword"].value;
    var secquestion = document.forms["regform"]["security_question"].value;
    var secanswer = document.forms["regform"]["security_answer"].value;

    if(role == "") {
        alert("Please select your role");
        return false;
    }
    if(firstname.trim() == "") {
        alert("First Name is required");
        return false;
    }
    if(!/^[A-Za-z]+$/.test(firstname)) {
        alert("First Name should contain letters only");
        return false;
    }
    if(lastname.trim() == "") {
        alert("Last Name is required");
        return false;
    }
    if(!/^[A-Za-z]+$/.test(lastname)) {
        alert("Last Name should contain letters only");
        return false;
    }
    if(username == "") {
        alert("Username is required");
        return false;
    }
    if(username.length < 4) {
        alert("Username must be at least 4 characters");
        return false;
    }
    if(phone.trim() == "") {
        alert("Phone number is required");
        return false;
    }
    if(email == "") {
        alert("Email is required");
        return false;
    }
    var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if(!emailPattern.test(email)) {
        alert("Please enter a valid email address");
        return false;
    }
    if(password == "") {
        alert("Password is required");
        return false;
    }
    if(password.length < 6) {
        alert("Password must be at least 6 characters");
        return false;
    }
    if(cpassword == "") {
        alert("Please confirm your password");
        return false;
    }
    if(password != cpassword) {
        alert("Passwords do not match");
        return false;
    }
    if(secquestion == "") {
        alert("Please select a security question");
        return false;
    }
    if(secanswer.trim() == "") {
        alert("Please provide an answer to your security question");
        return false;
    }

    if(role == "tour_guide") {
        var license = document.forms["regform"]["license_image"].value;
        var licensenum = document.forms["regform"]["license_number"].value;
        if(licensenum.trim() == "") {
            alert("Please enter your license number");
            return false;
        }
        if(license == "") {
            alert("Please upload your license");
            return false;
        }
    }

    return true;
}