
$(document).ready(function(){
    getOpenedNav();
});


$(".mobileNav").click(function(){
    $(".mobileNav").removeClass('active');
    $(this).addClass('active');
    var currentIndex = $(this).attr('alt');

    $('.content-area').children('div').fadeOut(100);
    $('.content-area').children('div').eq(currentIndex).fadeIn(100);

    localStorage.setItem('navIndex',currentIndex);
});



function getOpenedNav(){
    //set nav active item
    var navIndex = localStorage.getItem('navIndex');
    $(".mobileNav").removeClass('active');
    $('.content-area').children('div').fadeOut(100);

    $(".mobileNav").eq(navIndex).addClass('active');
    $('.content-area').children('div').eq(navIndex).fadeIn(100);
}


$('.select-all').click(function(){
    $('.customer > div input').click();
});
