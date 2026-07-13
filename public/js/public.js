

//JQUERY
$(document).ready(function(){
    sweetAlerts();
    hideLoadings();
});

//hide loadings
function hideLoadings(){
    $(".spinners").fadeOut(200);
    $('.dis').removeAttr('disabled');
}


// range input show value
$('#range').change(function(){
    let val = $(this).val();
    $('#percent').html(val+"%");
});

//Sweet Alert Roles
function sweetAlerts() {
    setTimeout(function(){
        $(".alert-bar").hide(200);
    },3000);
}
$(".alert-bar").click(()=>{
    $(".alert-bar").hide(200);
});




//Pure Java Script
function todayIs(){
    let today = moment().format('MMMM Do YYYY');
    let datePlace =document.getElementById('today');
    if(datePlace){datePlace.innerText = today}
}
window.addEventListener('load',todayIs());



function eMoNi(){
    let date = new Date();
    let hours = date.getHours();
    let result ="";
    let icon ="";

    var morning = '<i class="bi bi-brightness-high"></i>';
    var afternoon = '<i class="bi bi-cloud-sun"></i>';
    var Evening = '<i class="bi bi-cloud-moon"></i>';

    let iconPlace = document.getElementById('MoNi-icon');
    let lang = iconPlace ? iconPlace.getAttribute('alt') : 'en';

    if (hours < 12) {
        result = lang == "Ar"?  "صباح الخير" :  "Good Morning";
        icon = morning;
    } else if (hours < 18) {
        result = lang == "Ar"?  "طاب مسائك" :  "Good Afternoon";
        icon = afternoon;
    } else {
        result = lang == "Ar"?  "مساء الخير" : "Good Evening";
        icon = Evening;
    }


    if(iconPlace){iconPlace.innerHTML = icon}

    let moniPlace = document.getElementById('MoNi');
    if(moniPlace){moniPlace.innerText = result}
}

window.addEventListener('load', eMoNi());



/** ========== Snackbar Settings ========== */

function actionSnack(action){
    if(action == "show"){
        $('.snackbar').css('bottom','0px');
        console.log('show Snackbar');
    }else{
        if(!$('.snackbar').hasClass('show')){
            $('.snackbar').css('bottom','-57px');
            // console.log('hide Snackbar'.$('.snackbar').hasClass('show'));
        }else{
            $('.snackbar').css('bottom','0px');
        }
    }
}
