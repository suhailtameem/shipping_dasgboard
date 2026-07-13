

$("#newLine").click(function(ev){
    let temp = $("#linesTable").children('tr').eq(0).html();
    temp = "<tr>"+temp+"</tr>"
    $("#linesTable").append(temp);
});

$("body").on("click",".removeLine",function(ev){
    let eleIndex = $(this).parent('td').parent('tr').index();
    if(eleIndex != 0){
        $(this).parent('td').parent('tr').remove();
    }else{
        alert('First Row Cant Be Removed');
    }
});
