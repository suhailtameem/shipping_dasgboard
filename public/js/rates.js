$(document).ready(function(){
    ShippingsRatesLoading();
});
/*
    #Loading inecators
    .loading
    .shippingRatesControl
*/

function ShippingsRatesLoading(){
    $('.loading').fadeOut(3000);
    $('.shippingRatesControl .form-select , .shippingRatesControl .form-control,.shippingRatesControl .btn')
    .removeAttr('disabled');
}





//Contries and desnations by shipping types
/*
@sources
#airDests
#seaDests
#landDests


@event
.ShippingTypeSwitcher

@target
.shippingCountriesList
*/

$(".ShippingTypeSwitcher").change(function(){

    let shippingType = $(this).val();
    let Options;
    // console.log('shippingType'+shippingType);
    switch(shippingType){
        case '1':
            Options = $('#airDests').html();
            break;

        case '2':
            Options = $('#seaDests').html();
            break;

        case '3':
            Options = $('#landDests').html();
            break;
    }

    $(this).parent('td').next('td').children('.shippingCountriesList').html(Options);
    $(this).parent('td').next('td').next('td').children('.shippingCountriesList').html(Options);

});
