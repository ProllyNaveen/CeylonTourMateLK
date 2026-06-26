function validateWriteReview() {
    var rating = document.querySelector('input[name="rating"]:checked');
    var comment = document.getElementById("reviewComment").value;

    if (!rating) {
        alert("Please select a star rating");
        return false;
    }
    if (comment.trim() == "") {
        alert("Please write a comment before submitting");
        return false;
    }
    return true;
}