$( function() {
    $( ".datepicker" ).datepicker({
               changeMonth:true,
               changeYear:true,
               numberOfMonths:[1,1],
               yearRange: '1945:'+(new Date).getFullYear(),
               showAnim: "slide",
               appendText:"(yy-mm-dd)",
               dateFormat:"yy-mm-dd"
            });
} );