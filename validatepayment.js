function validatePayment() {
    var cardholder_name = document.forms["paymentform"]["cardholder_name"].value;
    var card_number = document.forms["paymentform"]["card_number"].value;
    var expiry_date = document.forms["paymentform"]["expiry_date"].value;
    var cvv = document.forms["paymentform"]["cvv"].value;

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

    // check expiry date is not in the past
    var parts = expiry_date.split('/');
    var expMonth = parseInt(parts[0]);
    var expYear = parseInt('20' + parts[1]);
    var now = new Date();
    var currentMonth = now.getMonth() + 1;
    var currentYear = now.getFullYear();

    if (expYear < currentYear || (expYear === currentYear && expMonth < currentMonth)) {
        alert("Your card has expired. Please use a valid card.");
        return false;
    }

    return true;
}

function formatCardNumber(input) {
    var value = input.value.replace(/\D/g, '');
    var formatted = value.match(/.{1,4}/g);
    input.value = formatted ? formatted.join(' ') : '';
}

function formatExpiry(input) {
    var value = input.value.replace(/\D/g, '');
    if (value.length >= 2) {
        value = value.substring(0, 2) + '/' + value.substring(2, 4);
    }
    input.value = value;
}