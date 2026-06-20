function validateSecurityAnswer() {
    var answer = document.forms["securityform"]["security_answer"].value;

    if(answer.trim() == "") {
        alert("Please enter your answer");
        return false;
    }

    return true;
}