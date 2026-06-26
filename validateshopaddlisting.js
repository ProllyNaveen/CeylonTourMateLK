function validateShopAddListing() {
    var title = document.forms["shopaddlistingform"]["title"].value;
    var description = document.forms["shopaddlistingform"]["description"].value;
    var location = document.forms["shopaddlistingform"]["location"].value;
    var city = document.forms["shopaddlistingform"]["city"].value;
    var price = document.forms["shopaddlistingform"]["price"].value;
    var shop_type = document.forms["shopaddlistingform"]["shop_type"].value;
    var open_time = document.forms["shopaddlistingform"]["open_time"].value;
    var close_time = document.forms["shopaddlistingform"]["close_time"].value;

    if (title == "" || description == "" || location == "" || city == "" || price == "" || shop_type == "" || open_time == "" || close_time == "") {
        alert("Please fill in all required fields");
        return false;
    }
    if (price <= 0) {
        alert("Price must be greater than 0");
        return false;
    }
    if (open_time >= close_time) {
        alert("Closing time must be after opening time");
        return false;
    }
    return true;
}