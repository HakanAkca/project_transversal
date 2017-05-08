$(function() {
    $('.form-container-2').foxholder({
        demo: 2 //or other number of demo (1-15) you want to use
    });

    $('#burger').click(function(){
        $('.mobile_dropdown').toggle("fast");
    })

});
