$(document).ready(function () {
    $('.nb-items').removeClass('active');
    // add active class to this current li
    $(this).addClass('active');
    // Get the current url of the web page
    var url = window.location;
    // Will only work if string in href matches with location
    $('.nav-bar a[href="' + url + '"]').parent().addClass('active');
    // Clicking the li
    /*
     $('.nb-items').click(
     function () {
     // Show the child's li
     // $(this).children('.sub-nav').show();
     // remove the active class of other li
     $('.nb-items').removeClass('active');
     // add active class to this current li
     $(this).addClass('active');
     }
     );*/
    // When you hover your mouse on this li
    $('.nb2-items').hover(
            function () {
                // Show the child's li when mouse hover
                $(this).children('.sub-nav').show();
            },
            function () {
                // Hidethe child's li when mouse not hover
                $(this).children('.sub-nav').hide();
            }
    );
    // Hide element where ever you clicked on the web page
    $(document).mouseup(function (e)
    {
        // Specify the element to hide
        var container = $('.notfications-items > .sub-nav');

        if (!container.is(e.target) // if the target of the click isn't the container...
                && container.has(e.target).length === 0) // ... nor a descendant of the container
        {
            // Hide the element
            container.hide();
        }
    });
});