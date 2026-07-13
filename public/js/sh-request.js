

$(document).ready(function(){
    requestListLoaded();
    prepareCountires($('.ShippingTypeSwitcher'));
    setShippingList();
    getTotalWeights();
    showHideSnacbar();
    lockTable();
    prepareSearchForm($('.ShipSelector').val());
    getTableTotalWeight();
    setSelectAlt();

});




//table lock
$("#addNewContent").change(function(){
    lockTable();
});

function lockTable(){
    if($("#addNewContent").is(":checked")){
        $(".newPackagesTable input ,.newPackagesTable select").removeAttr('disabled');
    }else{
        $(".newPackagesTable input ,.newPackagesTable select").attr('disabled','disabled');
    }
}

$(".newRow").click(function(){
    var row = $(".tbody").children("tr").eq(0).html();
    row = "<tr>"+row+"<tr>";
    $(".tbody").append(row);
    getTotalWeights()
});


$("body").on('click','.delRow',function(){
    let index = $(this).parent('td').parent('tr').index();
    console.log(index);
    if(index>0){
        $(this).parent('td').parent('tr').remove();
        getTotalWeights();
    }else{
        alert("Last row can't be removed");
    }
});



$("body").on('change','.Weights',function(){
    getTotalWeights();
});

//Calculate rates
function calcRates(){
    let air = [];
    let sea = [];
    let land = [];

    //get rate prices ranges for all shipping types
    for (let index = 0; index < $('.rates').length; index++) {
        let from,to,price,temp;
        switch($('.rates').eq(index).attr('title')){
            case '1':
                from = $('.rates').eq(index).val(); //[0]
                to = $('.rates').eq(index).attr('alt'); //[1]
                price = $('.rates').eq(index).attr('name');//[2]
                temp  = [from,to,price];
                air.push(temp);
            break;

            case '2':
                from = $('.rates').eq(index).val(); //[0]
                to = $('.rates').eq(index).attr('alt'); //[1]
                price = $('.rates').eq(index).attr('name');//[2]
                temp  = [from,to,price];
                sea.push(temp);
            break;

            case '3':
                from = $('.rates').eq(index).val(); //[0]
                to = $('.rates').eq(index).attr('alt'); //[1]
                price = $('.rates').eq(index).attr('name');//[2]
                temp  = [from,to,price];
                land.push(temp);
            break;


        }
        // console.log('type '+typeIs($('.rates').eq(index).attr('title'))+' From '+$('.rates').eq(index).val()+' To '+$('.rates').eq(index).attr('alt'));
    }
    console.log('rates '+$('.rates').length );

    let shippingType = $("#shippingType").val();
    let weight = parseFloat($("#TotalWeight").val());
    let price = 0.0;
    let from=0,to=0;

    // console.log('shippingType '+air.length);
    let rangesLength =0;
    switch(shippingType){
        case '1':
            rangesLength = air.length;
            console.log('Air Calculater');

            for(let i=0; i< rangesLength; i++){
                from = parseInt(air[i][0]);
                to = parseInt(air[i][1]);
                shPrice = parseFloat(air[i][2]);

                    // console.log(inRange(weight,from,to));
                if(inRange(weight,from,to)){
                    price = weight * shPrice;
                }else if((i+1)>=rangesLength){
                    console.log('Out of last range');
                    price = weight * shPrice;
                }else{}
            }
        break;

        case '2':
            rangesLength = sea.length;
            console.log('Sea Calculater');
            for(let i=0; i< sea.length; i++){

                from = parseInt(sea[i][0]);
                to = parseInt(sea[i][1]);
                shPrice = parseFloat(sea[i][2]);

                    // console.log(inRange(weight,from,to));
                if(inRange(weight,from,to)){
                    price = weight * shPrice;
                }else if((i+1)>=rangesLength){
                    console.log('Out of last range');
                    price = weight * shPrice;
                }else{}
            }
        break;

        case '3':
            rangesLength = land.length;
            console.log('Land Calculater');
            for(let i=0; i< land.length; i++){

                from = parseInt(land[i][0]);
                to = parseInt(land[i][1]);
                shPrice = parseFloat(land[i][2]);

                    // console.log(inRange(weight,from,to));
                if(inRange(weight,from,to)){
                    price = weight * shPrice;
                }else if((i+1)>=rangesLength){
                    console.log('Out of last range');
                    price = weight * shPrice;
                }else{}
            }
        break;
    }

    $('#TotalPrice').val(price);
    $('.TotalPrice').html(price);

}

function getTotalWeights(){
    let totalWeight = 0.0;
    let Rangeslength =$(".Weights").length;
    for (let index = 0; index < Rangeslength; index++) {
        totalWeight = totalWeight + parseFloat($(".Weights").eq(index).val());
    }
    $("#TotalWeight").val(totalWeight);
    $(".TotalWeight").html(totalWeight);
    //Then
    calcRates();
}

function inRange(n, nStart, nEnd){
    if(n>=nStart && n<=nEnd) return true;
    else return false;
}


function typeIs(TNO){
    switch(TNO){
        case'1': return "Air";
        case'2': return "Sea";
        case'3': return "land";
    }
}

function setSelectAlt(){
    for (let index = 0; index < $(".altValue").length; index++) {
        $(".altValue").eq(index).val($(".altValue").eq(index).attr('alt'));
    }

    // console.log('Alt values seted');
}

// ################# Request List page ###############

// Request lists lock
$(".unlock-all").change(function(){
    if($(this).is(':checked')){
        $("#request-list").find('input:text , select').removeAttr('disabled');
        //check boxs to true
        $("#request-list").find('input:checkbox').prop('checked',true);
        $(".checkers").find('span').addClass('checked');
    }else{
        $("#request-list").find('input:text , select').attr('disabled','disabled');
        //check boxs to false
        $("#request-list").find('input:checkbox').prop('checked',false);
        $(".checkers").find('span').removeClass('checked');
    }
});

//unlock row checkbox
$(".unlock-row").change(function(){
    if($(this).is(':checked')){
        $(this).closest('tr').find('input:text , select').removeAttr('disabled');
    }else{
        $(this).closest('tr').find('input:text , select').attr('disabled','disabled');
    }
});

//unlock row double click row
$("#request-list tr").dblclick(function(){
    $(this).find('input:checkbox').click()
});


$('.unlock-row , .unlock-all').change(function(){
    showHideSnacbar();
});

function showHideSnacbar(){
    if($('.dataTable input:checkbox:checked').length > 0){
        actionSnack('show');
    }else{
        actionSnack('hide');
    }
}

function requestListLoaded(){
    $('.loading').fadeOut(3000);
    $(".unlock-all, .unlock-row").removeAttr('disabled');
}

/*
.weightInputs
.tableTotalWeight
*/
function getTableTotalWeight(){
    let leng = $(".weightInputs").length;
    let totalWeight =0.0;
    for(let i=0; i< leng; i++){
        let weight = parseFloat($(".weightInputs").eq(i).val());
        totalWeight += weight;
    }
    $(".tableTotalWeight").html(totalWeight);
}



//========== Search Bar ==========
$(".search").click(function(ev){
    // ev.prevetDefault();
    let state= $(this).attr('alt');
    if(state == "false"){
        $(this).attr('alt','true');
        $(this).removeClass('btn-secondary').addClass('btn-primary');
        actionSnack('show');
    }else{
        $(this).attr('alt','false');
        $(this).removeClass('btn-primary').addClass('btn-secondary');
        actionSnack('hide');
    }
});

//prepare Shipments Lists
/**
    #AirCargos
    #SeaContainers
    #LandCharges
 */

$(".ShipmentsSelector").change(function(){
    prepareShipmentsList($(this));
});

function prepareShipmentsList(Target){
    let options ="<option value=''>NULL</option>";
    switch(Target.val()){
        case '1':
            options = $('#AirCargos').html();
            break;
        case '2':
            options = $('#SeaContainers').html();
            break;
        case '3':
            options = $('#LandCharges').html();
            break;
    }
    Target.closest('tr').find('.shipmentsLists').html(options);
}

function setShippingList(){
    for (let index = 0; index < $('.shipmentsLists').length; index++) {
        let shType = $('.shipmentsLists').eq(index).attr('title');
        let shipment = $('.shipmentsLists').eq(index).attr('alt');
        let options  ="";
        switch(shType){
            case '1':
                options = $('#AirCargos').html();
                break;
            case '2':
                options = $('#SeaContainers').html();
                break;
            case '3':
                options = $('#LandCharges').html();
                break;
        }
        $('.shipmentsLists').eq(index).html(options);
        $('.shipmentsLists').eq(index).val(shipment);
    }
}

//================== Search Filters Form =================
/**
 * ShipSelector
    ShipsList
 */

$('.ShipSelector').change(function(ev){
    let SHID = $(this).val();
    prepareSearchForm(SHID);
});

function prepareSearchForm(SHID){
    let options ="<option value=''>NULL</option>";
    switch(SHID){
        case '1':
            options = $('#AirCargos').html();
            break;
        case '2':
            options = $('#SeaContainers').html();
            break;
        case '3':
            options = $('#LandCharges').html();
            break;
    }
    $(".ShipsList").html(options);
}


//============= New Shipment Request Form =============
$(".ShippingTypeSwitcher").change(function(){
    prepareCountires($(this));
});

function prepareCountires(THIS){
    let shippingType = THIS.val();
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

    $('.countriesLists').html(Options);
}


