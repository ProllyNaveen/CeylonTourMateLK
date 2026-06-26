function validateShopPayFee() {
    var shop_listing_id = document.forms["shoppayfeeform"]["shop_listing_id"].value;
    var cardholder_name = document.forms["shoppayfeeform"]["cardholder_name"].value;
    var card_number = document.forms["shoppayfeeform"]["card_number"].value;
    var expiry_date = document.forms["shoppayfeeform"]["expiry_date"].value;
    var cvv = document.forms["shoppayfeeform"]["cvv"].value;

    if (shop_listing_id == "") {
        alert("Please select a listing to pay for");
        return false;
    }
    if (cardholder_name == "") {
        alert("Please enter the cardholder name");
        return false;
    }
    if (card_number.replace(/\s/g, '').length != 16) {
        alert("Please enter a valid 16 digit card number");
        return false;
    }
    if (expiry_date.length != 5) {
        alert("Please enter a valid expiry date in MM/YY format");
        return false;
    }
    if (cvv.length != 3) {
        alert("Please enter a valid 3 digit CVV");
        return false;
    }
    return true;
}

function selectListing(shopListingId, listingName) {
    document.getElementById("shop_listing_id").value = shopListingId;
    document.getElementById("selected-listing-name").textContent = listingName;
    // PHP will provide the fee amount — for now set a placeholder
    document.getElementById("amount").value = "500.00";
}