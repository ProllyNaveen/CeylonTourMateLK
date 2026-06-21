function validateCommissionRate() {
    var rate = document.forms["commissionform"]["rate"].value;

    if(rate === "") {
        alert("Please enter a commission rate");
        return false;
    }
    if(rate < 0 || rate > 100) {
        alert("Commission rate must be between 0 and 100");
        return false;
    }

    return true;
}