function validateMakeBooking() {
    var start_datetime = document.forms["makebookingform"]["start_datetime"].value;
    var end_datetime = document.forms["makebookingform"]["end_datetime"].value;
    var guest_count = document.forms["makebookingform"]["guest_count"].value;
    var listing_type = document.getElementById("listing_type").value;

    if (start_datetime == "") {
        alert("Please select a start date and time");
        return false;
    }
    if (end_datetime == "") {
        alert("Please select an end date and time");
        return false;
    }
    if (start_datetime >= end_datetime) {
        alert("End date and time must be after start date and time");
        return false;
    }
    if (guest_count == "" || guest_count <= 0) {
        alert("Please enter a valid number of guests");
        return false;
    }
    if (listing_type == "hotel") {
        var rooms = document.querySelectorAll('input[name="room_ids[]"]:checked');
        if (rooms.length == 0) {
            alert("Please select at least one room");
            return false;
        }
    }
    return true;
}