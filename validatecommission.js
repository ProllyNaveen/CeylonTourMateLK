function validateCommissionRate() {
    var rate = document.forms["commissionform"]["rate"].value;

    if (rate === "") {
        alert("Please enter a commission rate");
        return false;
    }

    if (rate < 0 || rate > 100) {
        alert("Commission rate must be between 0 and 100");
        return false;
    }

    return true;
}

function validateListingFee() {
    var fee = document.forms["listingfeeform"]["listing_fee"].value;

    if (fee === "") {
        alert("Please enter a listing fee");
        return false;
    }

    if (fee < 0) {
        alert("Listing fee cannot be negative");
        return false;
    }

    return true;
}