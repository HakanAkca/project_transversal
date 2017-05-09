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

    function switch_tab($tabs, $content){
        $('.changing_content').css('display', 'none');
        $content.css('display', 'block');
        $('.tabs').removeClass('active');
        $tabs.addClass('active');
    }

    $('#profile_user_reductions').click(function(){
        switch_tab($('#profile_user_reductions'), $('.my_reductions'))
    });

    $('#profile_availables_reductions').click(function(){
        switch_tab($('#profile_availables_reductions'), $('.available_reductions'))
    });

    $('#profile_sondage').click(function(){
        switch_tab($('#profile_sondage'), $('.sondage'))
    });

});
