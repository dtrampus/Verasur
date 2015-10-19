var verasurDownloadIngress = function () {

    var total = verasurMain.redondeo($("#ingress-real-liter").val(),2);
    
    var Initialize = function () {
 
        initTanks();
        initRules();
        $('#download-form').validate({
            onkeyup: false,
            submitHandler: function(form) {
                form.submit();
                $("#submitform").prop('disabled', true);
            },
            rules: {
                tanque1 : {
                    required : true,
                    notEqual : true,
                    minRealLiters: true,
                    minFreeCapacity1: true
                },
                cantidad1 : {
                    required : true,
                    number : true,
                    minRealLiters: true,
                    minFreeCapacity1: true,
                    lessRealLiter : true
                },
                cantidad2: {
                    required: false,
                    number: true,
                    minFreeCapacity2: true
                },
                tanque2 : {
                    required : false,
                    notEqual : true
                }
            },
            messages: {
                tanque1 : {
                    required : "Este campo es requerido"
                },
                cantidad1:{
                    required : "Este campo es requerido",
                    number : "El valor ingresado debe ser un número"
                }
            }
        });

        //Eventos
        $("#tanque1").change(function () {
            var value = $("#tanque1").val();
            if (value != "") {
                $.ajax({
                    type: 'GET',
                    url: Routing.generate('calculateCapacity', {id: value}),
                    dataType: "json",
                    success: function (jsonTank) {
                        $('#tank1Msg').html("La cantidad libre es " + jsonTank['tankFreeCapacity']);
                        $("#tank1FreeCapacity").val(jsonTank['tankFreeCapacity']);
                    }
                });
            }
        });

        $("#tanque2").change(function () {
            var value = $("#tanque2").val();
            if (value != "") {
                $.ajax({
                    type: 'GET',
                    url: Routing.generate('calculateCapacity', {id: value}),
                    dataType: "json",
                    async: false,
                    success: function (jsonTank) {
                        $('#tank2Msg').html("La cantidad libre es " + jsonTank['tankFreeCapacity']);
                        $("#tank2FreeCapacity").val(jsonTank['tankFreeCapacity']);
                        $(".cantidad1").trigger("focusout");
                    }
                });
                calculateRest();
            }else{
                $(".cantidad1").val(total);
                $(".cantidad2").val("");
                $(".cantidad1").trigger("focusout");
            }
        });

        $(".cantidad1").focusout(function () {
            var quantity1 = verasurMain.redondeo($(this).val(),2);
            $(this).val(quantity1);
            if($(this).closest(".form-group").hasClass("has-error") == false){
                calculateRest();
            }
        });
    };
    
    //Se crean las reglas personalizadas
    var initRules = function(){
        jQuery.validator.addMethod("notEqual", function(value, element, param) {
            return $('#tanque1').val() != $('#tanque2').val();
        }, "Los tanques son iguales");

        jQuery.validator.addMethod("minRealLiters", function (value, element, param) {
            return verasurMain.redondeo($('.cantidad1').val(),2) <= total;
        }, "La cantidad no puede superar los litros totales");

        jQuery.validator.addMethod("minFreeCapacity1", function (value, element, param) {
            var value = $("#tanque1").val();
            var retorno = true;
            if (value != "") {
                $.ajax({
                    type: 'GET',
                    url: Routing.generate('calculateCapacity', {id: value}),
                    dataType: "json",
                    async:false,
                    success: function (jsonTank) {
                        retorno = verasurMain.redondeo($('.cantidad1').val(),2) <= verasurMain.redondeo(jsonTank['tankFreeCapacity'],2);
                    }
                });
            }
            if ($(element).attr("id")=="tanque1" && $('.cantidad1').val() != ""){
                $('.cantidad1').valid();
            }
            return retorno;
        }, "La cantidad no puede superar los litros libres del tanque");

        jQuery.validator.addMethod("minFreeCapacity2", function (value, element, param) {
            return verasurMain.redondeo($('.cantidad2').val(),2) <= verasurMain.redondeo($('#tank2FreeCapacity').val(),2);
        }, "La cantidad no puede superar los litros libres del tanque");

        jQuery.validator.addMethod("lessRealLiter", function (value, element, param) {
            return verasurMain.redondeo(total,2) == verasurMain.redondeo($(".cantidad1").val(),2)+verasurMain.redondeo($(".cantidad2").val(),2);
        }, "Las suma de las cantidades ingresadas deber ser igual a los litros totales");
    };
    
    var initTanks = function(){
        $.ajax({
            type: 'GET',
            url: Routing.generate('getTanksAjax', {id: $("#ingress-product").val()}),
            dataType: "json",
            success: function (jsonTanks) {
                $('#tanque1, #tanque2').html("<option value=''>Elige un tanque</option>");
                for (var tank in jsonTanks) {
                    var arrayTank = jsonTanks[tank];
                    $('#tanque1, #tanque2').append("<option value=" + arrayTank[0] + ">" + arrayTank[1] + " - " + arrayTank[2] + "</option>");
                }
            }
        });
    };
    
    var calculateRest = function(){
        var quantity1 = verasurMain.redondeo($(".cantidad1").val(),2);
        var realLiter = $("#ingress-real-liter").val();
        var rest = verasurMain.redondeo(realLiter - quantity1,2);
        if (rest <= 0) {
            $(".cantidad2").val("");
        } else {
            if ($("#tanque2").val() != "") {
                $(".cantidad2").val(rest);
            }
        }
        $(".cantidad2").trigger("focusout");
    };
    
    return {
        init: Initialize
    };

}();