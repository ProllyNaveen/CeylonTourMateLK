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