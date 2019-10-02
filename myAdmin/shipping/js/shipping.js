$(document).ready(function(){

    pricePerWeight  =   function(){
        $('#perkg').text( $("#shpWeight  option:selected").text() );
    }
        pricePerWeight();

    $("#shpWeight").change(function(){
        pricePerWeight();
    });

    $("#fromCountry").change(function(){
        $("#currency").val($(this).val()).change();
    });


});


function submitCheck(){
    from        =   $("#fromCountry").val();
    currency    =   $("#currency").val();
    fromText    =   $("#fromCountry option:selected").text();
    currencyText=   $("#currency option:selected").text();
    if(from != currency){
      c =  confirm("Country "+fromText+" using "+currencyText+" Currency \n " +
                "It will effect in order price \n" +
                "Are you sure you want to Add, press OK");
      if(c)return true;
        return false;
    }
    return true;

}