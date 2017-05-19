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

    $('.open_login_md').click(function(){
        $('.modale_login').css('display', 'block');
        $('body').css('overflow', 'hidden');
    })

    $('.close_login_md').click(function(){
        $('.modale_login').css('display', 'none');
        $('body').css('overflow', 'initial');
    })

    $(document).on('click','.next_2',function(){
        $('.bottle').css('display', 'block');
        if( $(window).width() < 768){
            $('.bottle').css('animation-name', "throw_bottle");
        }
        else if( $(window).width() < 1024 ){
            $('.bottle').css('animation-name', "throw_bottle_tablet");
        }else{
            $('.bottle').css('animation-name', "throw_bottle_desktop");
        }
        $('.girl_phone').css({"margin-top": "140px", "margin-left": "25px"});
        $('.girl_bottle').css('display', 'none');
        $('.girl_phone').css('display', 'inline');
        setTimeout(function(){
            $('.bottle').css('display', 'none');
        }, 1000);
        $(this).removeClass('next_2');
        $(this).addClass('next_3');
        $('.how_many_steps').html('2/3');
    });

    $(document).on('click','.next_3',function(){
        if( $(window).width() < 1024 &&  $(window).width() > 767){
            $('.girl_phone').css('animation-name', "move_to_trash_tablet")
            setTimeout(function(){
                $('.girl_phone').css({"margin-top": "125px", "margin-left": "295px"});
            }, 1000);
        }
        if ($(window).width() > 1023){
            $('.girl_phone').css('animation-name', "move_to_trash_desktop")
            $('.girl_phone').css({"margin-top": "125px", "margin-left": "475px"});
        }
        $('.step_one').fadeOut(500, function(){
             $('.step_two').fadeIn(500);
        });
        $(this).removeClass('next_3');
        $(this).addClass('next_1');
        $('.how_many_steps').html('3/3');
    })

    $(document).on('click', '.next_1', function(){
         $('.girl_phone').css('animation-name', "");
        $('.step_two').fadeOut(500, function(){
             $('.step_one').fadeIn(500);
        });
        $('.girl_bottle').css('display', 'inline');
        $('.girl_phone').css('display', 'none');
        $(this).removeClass('next_1');
        $(this).addClass('next_2');
        $('.how_many_steps').html('1/3');
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

    $('.open_md').click(function(){
        $('#modale').css('display', 'flex');
        $('body').css('overflow', 'hidden');
       
        $('#modale').children('.modale_available_deal').children('.deal_info').children('.img_modale').attr("src", $(this).parent().parent().children(".partner_img").attr("src"));
        $('#modale').children('.modale_available_deal').children('.deal_info').children('.info').children('.partner_modale').children('span:last').html($(this).parent().parent().children(".deal_info").children('span:first').html());
        $('#modale').children('.modale_available_deal').children('.deal_info').children('.info').children('.city_modale').children('span:last').html($(this).parent().parent().children(".other_info").children('span:first').html());
        $('#modale').children('.modale_available_deal').children('.deal_info').children('.info').children('.expiration_modale').children('span:last').html($(this).parent().parent().children(".other_info").children('span:last').html());
        $('#modale').children('.modale_available_deal').children('.deal_info').children('.info').children('.important').children('.price').children('span:last').html($(this).parent().parent().children(".deal_cost").children('.open_md').children('span:first').html() + ' points');
        $('#modale').children('.modale_available_deal').children('.deal_info').children('.info').children('.important').children('.reduc').children('span:last').html($(this).parent().parent().children(".deal_info").children('span:last').html());

        $('#modale').children('.modale_available_deal').children('.action').children('.buy_deal').children('input:first').attr('value', $(this).parent().children(".other_info").children('.id').html());
    })

    $('.more').click(function(){
        $('#modale').css('display', 'flex');
        $('body').css('overflow', 'hidden');

        $('#modale').children('.modale_available_deal').children('.deal_info').children('.img_modale').attr("src", $(this).parent().parent().parent().children("img").attr("src"));
        $('#modale').children('.modale_available_deal').children('.deal_info').children('.info').children('.partner_modale').children('span:last').html($(this).parent().parent().children(".title").html());
        $('#modale').children('.modale_available_deal').children('.deal_info').children('.info').children('.city_modale').children('span:last').html($(this).parent().parent().children(".more_info").children('span:first').html());
        $('#modale').children('.modale_available_deal').children('.deal_info').children('.info').children('.expiration_modale').children('span:last').html($(this).parent().parent().children(".more_info").children('span:last').html());
        $('#modale').children('.modale_available_deal').children('.deal_info').children('.info').children('.important').children('.price').children('span:last').html($(this).parent().children(".costs").html());
        $('#modale').children('.modale_available_deal').children('.deal_info').children('.info').children('.important').children('.reduc').children('span:last').html($(this).parent().parent().children(".more_info").children('p:first').html());

        $('#modale').children('.modale_available_deal').children('.action').children('.buy_deal').children('input:first').attr('value', $(this).parent().parent().children(".more_info").children('.id').html());
    })

    $('.close_modale').click(function(){
        $('#modale').css('display', 'none');
        $('body').css('overflow', 'initial');        
    })

    $('.insert_code').click(function(){
        $('.form_code').css('display', 'block');
        $('.insert').css('background-color', 'rgba(0,0,0,0.4)');
    })

    $('.params').click(function(){
        $('.stats').css('display', 'none');
        $('.box').css('display', 'none');
        $('.insert').css('display', 'none');
        $('.user_edit').css('display', 'block');
        $('.all_offers_link').css('display', 'none');
    })

});
