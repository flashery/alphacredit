$(document).ready(function () {
    // Show the element when you click its parent
    $('li.notfications-items').click(
            function () {
                $(this).children('.sub-nav').show();
            }
    );
    $('#hidden-menu').click(
            function () {
                $('#nav_menu').toggle("fast").css("background-color:#738E96");
            }
    );
    // Hiding of elements already done in sidebar.js

});