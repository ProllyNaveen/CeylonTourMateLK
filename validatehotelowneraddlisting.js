function validateHotelOwnerAddListing() {
    var title = document.forms["hotelowneraddlistingform"]["title"].value;
    var description = document.forms["hotelowneraddlistingform"]["description"].value;
    var location = document.forms["hotelowneraddlistingform"]["location"].value;
    var city = document.forms["hotelowneraddlistingform"]["city"].value;
    var price = document.forms["hotelowneraddlistingform"]["price"].value;
    var star_rating = document.forms["hotelowneraddlistingform"]["star_rating"].value;
    var amenities = document.forms["hotelowneraddlistingform"]["amenities"].value;
    var total_rooms = document.forms["hotelowneraddlistingform"]["total_rooms"].value;
    var check_in_time = document.forms["hotelowneraddlistingform"]["check_in_time"].value;
    var check_out_time = document.forms["hotelowneraddlistingform"]["check_out_time"].value;

    if (title == "" || description == "" || location == "" || city == "" || price == "" || star_rating == "" || amenities == "" || total_rooms == "" || check_in_time == "" || check_out_time == "") {
        alert("Please fill in all required fields");
        return false;
    }
    if (price <= 0) {
        alert("Price must be greater than 0");
        return false;
    }
    if (total_rooms <= 0) {
        alert("Total rooms must be at least 1");
        return false;
    }
    if (check_in_time >= check_out_time) {
        alert("Check-out time must be after check-in time");
        return false;
    }
    return true;
}