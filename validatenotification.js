function validateNotification() {
    var send_to = document.forms["notificationform"]["send_to"].value;
    var message = document.forms["notificationform"]["message"].value;

    if (send_to == "") {
        alert("Please select who to send the notification to");
        return false;
    }
    if (send_to == "specific") {
        var receiver_id = document.forms["notificationform"]["receiver_id"].value;
        if (receiver_id == "") {
            alert("Please select a specific user");
            return false;
        }
    }
    if (message == "") {
        alert("Please enter a message");
        return false;
    }
    return true;
}

function toggleUserSelect() {
    var send_to = document.getElementById("send_to").value;
    var specificDiv = document.getElementById("specific-user-div");
    if (send_to == "specific") {
        specificDiv.style.display = "block";
    } else {
        specificDiv.style.display = "none";
    }
}