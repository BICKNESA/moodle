$(document).ready(function() { // make sure the page is ready for jquery

    // this is a one line comment

    /* this is
    a multi line comment */

    $('#vote_button').click(function(event) {  // when the submit button gets clicked

        if ($("input:checked").length == 0) {  // if the there are no inputs that are checked
            event.preventDefault(); // stop the functionality of the click on the submit button
            alert('You need to select a movie'); // tell the user
        }

    });

    $('.poster').click(function() {

        $('.poster').css('border', '4px solid #ffffff');
        $('.poster').css('height', '150px');
        $('.poster').css('margin-bottom', '20px');
        $('.poster').css('margin-top', '20px');

        $(this).css('border', '4px solid red');
        $(this).animate({
            'height': '180px', // note that I change the height not the width to prevent a bug with more that 4 movies
            'margin-bottom': '10px',
            'margin-top': '0px'
        });

    });

});
