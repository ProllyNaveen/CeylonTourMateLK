function validateAddRoom() {
    var room_type = document.forms["addroomform"]["room_type"].value;
    var description = document.forms["addroomform"]["description"].value;
    var price_per_night = document.forms["addroomform"]["price_per_night"].value;
    var capacity = document.forms["addroomform"]["capacity"].value;

    if (room_type == "" || description == "" || price_per_night == "" || capacity == "") {
        alert("Please fill in all required fields");
        return false;
    }
    if (price_per_night <= 0) {
        alert("Price per night must be greater than 0");
        return false;
    }
    if (capacity <= 0) {
        alert("Capacity must be at least 1");
        return false;
    }
    return true;
}