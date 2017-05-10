$(function() {
    $('.form-container-2').foxholder({
        demo: 2 //or other number of demo (1-15) you want to use
    });

    $('.burger').click(function(){
        if($('.mobile_dropdown').is(':visible')){
            $('.mobile_dropdown').animate({'left' :"-100%"}, 500, function(){
                $('.mobile_dropdown').css({'display': 'none', 'left':'-100%'});
                $('body').css('overflow', 'initial');
            })
        }
        else{
            $('.mobile_dropdown').css({'display': 'flex', 'left':'-100%'});
            $('.mobile_dropdown').animate({'left' :"0"}, 500 )
            $('body').css('overflow', 'hidden');
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
        $('.all_offers_link').css('display', 'none');
    });

    $('#profile_availables_reductions').click(function(){
        switch_tab($('#profile_availables_reductions'), $('.available_reductions'))
        $('.all_offers_link').css('display', 'block');
    });

    $('#profile_sondage').click(function(){
        switch_tab($('#profile_sondage'), $('.sondage'))
        $('.all_offers_link').css('display', 'none');
    });

    $('.deal_cost').click(function(){
        $('#modale').css('display', 'flex');
        $('body').css('overflow', 'hidden');
       
        $('#modale').children('.modale_available_deal').children('.deal_info').children('.img_modale').attr("src", $(this).parent().children(".partner_img").attr("src"));
        $('#modale').children('.modale_available_deal').children('.deal_info').children('.info').children('.partner_modale').children('span:last').html($(this).parent().children(".deal_info").children('span:first').html());
        $('#modale').children('.modale_available_deal').children('.deal_info').children('.info').children('.city_modale').children('span:last').html($(this).parent().children(".other_info").children('span:first').html());
        $('#modale').children('.modale_available_deal').children('.deal_info').children('.info').children('.expiration_modale').children('span:last').html('slt');
        $('#modale').children('.modale_available_deal').children('.deal_info').children('.info').children('.important').children('.price').children('span:last').html($(this).parent().children(".deal_cost").children('span:first').html() + ' points');
        $('#modale').children('.modale_available_deal').children('.deal_info').children('.info').children('.important').children('.reduc').children('span:last').html($(this).parent().children(".deal_info").children('span:last').html());

        $('#modale').children('.modale_available_deal').children('.action').children('.buy_deal').children('input:first').attr('value', $(this).parent().children(".other_info").children('.id').html());
    })

    $('.close_modale').click(function(){
        $('#modale').css('display', 'none');
        $('body').css('overflow', 'initial');        
    })

});
