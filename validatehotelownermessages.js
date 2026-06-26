function validateReply() {
    var message = document.getElementById("replyMessage").value;
    if (message.trim() == "") {
        alert("Please enter a message before sending");
        return false;
    }
    return true;
}