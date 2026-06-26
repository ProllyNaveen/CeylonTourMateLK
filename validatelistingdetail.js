function validateMessage() {
    var message = document.getElementById("messageText").value;
    if (message.trim() == "") {
        alert("Please enter a message before sending");
        return false;
    }
    return true;
}