$(function() {
    $('.form-container-2').foxholder({
        demo: 2 //or other number of demo (1-15) you want to use
    });

    $('.burger').click(function(){
        if($('.mobile_dropdown').is(':visible')){
            $('.mobile_dropdown').animate({'left' :"-100%"}, 1000, function(){
                $('.mobile_dropdown').css({'display': 'none', 'left':'-100%'});
            })
        }
        else{
            $('.mobile_dropdown').css({'display': 'flex', 'left':'-100%'});
            $('.mobile_dropdown').animate({'left' :"0"}, 1000 )
        }
    })
});
