// This ensures the script runs only after the page is fully loaded.
$(document).ready(function () {
    // This line listens for a 'click' event on the button with id 'submit-review'.
    $('#submit-review').on('click', function () {
        
        // Get the values from the input fields.
        const name = $('#reviewer-name').val().trim();
        const date = $('#review-date').val().trim();
        const message = $('#review-message').val().trim();

        // Check if any of the fields are empty.
        if (!name || !date || !message) {
            alert('Please fill out all fields.');
            return; // Stop the function if fields are empty.
        }

        // Add the new review box to the beginning of the review container.
        $('.review .box-container').prepend(reviewHTML);

        // Clear the form fields after submission.
        $('#reviewer-name').val('');
        $('#review-date').val('');
        $('#review-message').val('');
    });
});