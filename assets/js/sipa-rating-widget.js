/**
 * Created by Goksel on 27.01.2017.
 */

if(typeof jQuery!='undefined')
{
    if(typeof $=='undefined') {$ = jQuery};
}

$(function () {


    $(document).on('click','._g_sipa-rating-rate span._ayg.user', function (e) {

        var posX2 = jQuery(this).offset().left, posY2 = jQuery(this).offset().top;

        var width = (e.pageX - posX2);
        /*var height = (e.pageY - posY2);*/
        var rate = 1;
        var that = this;

        if(width < 18) width = 17; /* 1 star */
        else if(width > 17 && width < 34 ) { width = 35; rate = 2; /* 2 stars */}
        else if(width > 34 && width < 50 ) {width = 51; rate = 3; /* 3 stars */}
        else if(width > 51 && width < 67 ) {width = 68; rate = 4;/* 4 stars */ }
        else if(width > 68 ) {width = 85; rate=5; /* 5 stars */}

        $(this).find('span').css({'width':width});

        var sp = $(this).parents('._g_sipa-rating');
        var token = sp.attr('data-tkn');

        $.ajax({
            url : sp.attr('data-url'),
            dataType : "json",
            type : "post",
            data: {
                action: sp.attr('data-action'),
                /*action: "sipa-do-rate",*/
                post_id : sp.attr('data-post') ,
                rate:rate,
                token : token
            },
            success: function(data) {
                if(data.rate)
                    $(that).find('span').css({'width':getWidthVal(data.rate)});
                if(data.token_srv)
                {
                    if(token != data.token_srv)
                        $('._g_sipa-rating').attr('data-tkn',data.token_srv);

                    if(data.msg)
                    {
                        sp.find('._g_msg').html(data.msg);
                        sp.find('._g_msg').animate({opacity:1},1000);
                    }
                }
                else
                if(data.msg)
                {
                    sp.find('._g_msg').html(data.msg);
                    sp.find('._g_msg').animate({opacity:1},1000);
                }

                setTimeout(function(){
                    sp.find('._g_msg').animate({opacity:0},1000);
                },3000);
            }
        });

    });



    $(document).ready(function (e) {
        var sp = $('._g_sipa-rating');

        sp.each(function () {
            var sps = $(this).find('span._ayg.user span');
            var spavg = $(this).attr('data-avg')*1;

            var width = 85;

            if(spavg <= 0 ) width = 0; /* 0 star */
            else if(spavg > 0 && spavg < 0.7 ) width = 8.5; /* 0.5 star */
            else if(spavg > 0.6 && spavg < 1.4 ) width = 17; /* 1 star */
            else if(spavg > 1.3 && spavg < 1.7 ) width = 26.5; /* 1,5 stars */
            else if(spavg > 1.6 && spavg < 2.2 ) width = 35; /* 2 stars */
            else if(spavg > 2.2 && spavg < 2.7 ) width = 43.5; /* 2,5 stars */
            else if(spavg > 2.6 && spavg < 3.2 ) width = 51; /* 3 stars */
            else if(spavg > 3.2 && spavg < 3.7 ) width = 59.5; /* 3,5 stars */
            else if(spavg > 3.6 && spavg < 4.1 ) width = 68; /* 4 stars */
            else if(spavg > 4.0 && spavg < 4.7 ) width = 76.5; /* 4,5 stars */
            else if(spavg > 4.6 ) width = 85; /* 5 stars */

            sps.css({'width':width});
        });
    });


});

function getWidthVal(spavg)
{
    var width = 0;
    if(spavg <= 0 ) width = 0; /* 0 star */
    else if(spavg > 0 && spavg < 0.7 ) width = 8.5; /* 0.5 star */
    else if(spavg > 0.6 && spavg < 1.4 ) width = 17; /* 1 star */
    else if(spavg > 1.3 && spavg < 1.7 ) width = 26.5; /* 1,5 stars */
    else if(spavg > 1.6 && spavg < 2.2 ) width = 35; /* 2 stars */
    else if(spavg > 2.2 && spavg < 2.7 ) width = 43.5; /* 2,5 stars */
    else if(spavg > 2.6 && spavg < 3.2 ) width = 51; /* 3 stars */
    else if(spavg > 3.2 && spavg < 3.7 ) width = 59.5; /* 3,5 stars */
    else if(spavg > 3.6 && spavg < 4.1 ) width = 68; /* 4 stars */
    else if(spavg > 4.0 && spavg < 4.7 ) width = 76.5; /* 4,5 stars */
    else if(spavg > 4.6 ) width = 85; /* 5 stars */

    return width;

}