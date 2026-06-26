function validateExperienceAddListing() {
    var title = document.forms["experienceaddlistingform"]["title"].value;
    var description = document.forms["experienceaddlistingform"]["description"].value;
    var location = document.forms["experienceaddlistingform"]["location"].value;
    var city = document.forms["experienceaddlistingform"]["city"].value;
    var price = document.forms["experienceaddlistingform"]["price"].value;
    var experience_type = document.forms["experienceaddlistingform"]["experience_type"].value;
    var duration = document.forms["experienceaddlistingform"]["duration"].value;
    var max_group_size = document.forms["experienceaddlistingform"]["max_group_size"].value;
    var availability = document.forms["experienceaddlistingform"]["availability"].value;

    if (title == "" || description == "" || location == "" || city == "" || price == "" || experience_type == "" || duration == "" || max_group_size == "" || availability == "") {
        alert("Please fill in all required fields");
        return false;
    }
    if (price <= 0) {
        alert("Price must be greater than 0");
        return false;
    }
    if (max_group_size <= 0) {
        alert("Maximum group size must be at least 1");
        return false;
    }
    return true;
}