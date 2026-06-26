function validateTourGuideAddListing() {
    var title = document.forms["tourguideaddlistingform"]["title"].value;
    var description = document.forms["tourguideaddlistingform"]["description"].value;
    var location = document.forms["tourguideaddlistingform"]["location"].value;
    var city = document.forms["tourguideaddlistingform"]["city"].value;
    var price = document.forms["tourguideaddlistingform"]["price"].value;
    var languages = document.forms["tourguideaddlistingform"]["languages"].value;
    var specialization = document.forms["tourguideaddlistingform"]["specialization"].value;
    var experience_years = document.forms["tourguideaddlistingform"]["experience_years"].value;
    var availability = document.forms["tourguideaddlistingform"]["availability"].value;

    if (title == "" || description == "" || location == "" || city == "" || price == "" || languages == "" || specialization == "" || experience_years == "" || availability == "") {
        alert("Please fill in all required fields");
        return false;
    }
    if (price <= 0) {
        alert("Price must be greater than 0");
        return false;
    }
    if (experience_years < 0) {
        alert("Experience years cannot be negative");
        return false;
    }
    return true;
}