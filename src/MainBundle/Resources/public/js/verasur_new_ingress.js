var verasurNewIngress = function () {

    var Initialize = function () {
        //Se quita la clase form-control para que los select2 se vean bien
        $("select").removeClass("form-control");

        initTransport();
        initProduct();
        initMuestra();
        
        //Validation
        $('#ingress-form').validate({
            onkeyup: false,
            submitHandler: function(form) {
                form.submit();
                $("#mainbundle_ingress_submit").prop('disabled', true);
            },
            rules: {
                "mainbundle_ingress[truckDomain]":{
                    required :true
                },
                "mainbundle_ingress[coupledDomain]":{
                    required :true
                },
                "mainbundle_ingress[grossWeight]":{
                    required :true,
                    number: true
                },
                "mainbundle_ingress[tareWeight]":{
                    required :true,
                    number: true
                },
                "mainbundle_ingress[density]":{
                    required :true,
                    number: true
                },
                "mainbundle_ingress[clean]":{
                    required :true
                },
                "mainbundle_ingress[realLiter]":{
                    required :true
                },
                "mainbundle_ingress[provider]":{
                    required :true
                },
                "mainbundle_ingress[product]":{
                    required :true
                },
                "mainbundle_ingress[transport]":{
                    required :true
                },
                "mainbundle_ingress[driver]":{
                    required :true
                }
            },
            messages: {
                "mainbundle_ingress[truckDomain]":{
                    required : "Este campo es requerido"
                },
                "mainbundle_ingress[coupledDomain]":{
                    required :"Este campo es requerido"
                },
                "mainbundle_ingress[grossWeight]":{
                    required :"Este campo es requerido",
                    number: "El valor debe ser numerico"
                },
                "mainbundle_ingress[tareWeight]":{
                    required :"Este campo es requerido",
                    number: "El valor debe ser numerico"
                },
                "mainbundle_ingress[density]":{
                    required :"Este campo es requerido",
                    number: "El valor debe ser numerico"
                },
                "mainbundle_ingress[clean]":{
                    required :"Este campo es requerido"
                },
                "mainbundle_ingress[realLiter]":{
                    required :"Este campo es requerido"
                },
                "mainbundle_ingress[provider]":{
                    required :"Este campo es requerido"
                },
                "mainbundle_ingress[product]":{
                    required :"Este campo es requerido"
                },
                "mainbundle_ingress[transport]":{
                    required :"Este campo es requerido"
                },
                "mainbundle_ingress[driver]":{
                    required :"Este campo es requerido"
                }
            }
        });
    };
    
    var initTransport = function(){
        //Refresca los choferes cuando se selecciona un transporte
        $("#mainbundle_ingress_transport").change(function () {
            var value = $(this).val();
            if (value != '' && typeof value != "undefined") {
                $.ajax({
                    type: 'GET',
                    url: Routing.generate('getDriverAjax', {id: value}),
                    dataType: "json",
                    success: function (jsonDrivers) {
                        $('#mainbundle_ingress_driver option').addClass("hide");
                        for (var driver in jsonDrivers) {
                            var arrayDriver = jsonDrivers[driver];
                            $('#mainbundle_ingress_driver option[value="' + arrayDriver[0] + '"]').removeClass("hide");
                        }
                    }
                });
            }else{
                $('#mainbundle_ingress_driver').select2("val","");
                $('#mainbundle_ingress_driver option').addClass("hide");
                $('#mainbundle_ingress_driver option[value=""]').removeClass("hide");                
            }
        }); 
    };
    
    var initProduct = function(){
        //Se realizan los calculos sobre el neto y los litros reales
        $("#mainbundle_ingress_grossWeight,#mainbundle_ingress_tareWeight,#mainbundle_ingress_density").keyup(function () {
            $("#mainbundle_ingress_clean").val(calculateClean());
            $("#mainbundle_ingress_realLiter").val(calculateRealLiters());
        });

        //Formateo de campos de texto
        $("#mainbundle_ingress_grossWeight,#mainbundle_ingress_tareWeight").focusout(function () {
            $(this).val(verasurMain.redondeo($(this).val(), 2));
        });
        $("#mainbundle_ingress_density").focusout(function () {
            $(this).val(verasurMain.redondeo($(this).val(), 3));
        });
    };
    
    var initMuestra = function(){
        //Se redondea todos los valores de las muestras
        $('.floatNumberPrecision').focusout(function(){
            $(this).val(verasurMain.redondeo($(this).val(),1));
        });
    };
    
    var calculateClean = function () {
        var grossWeight = verasurMain.redondeo($("#mainbundle_ingress_grossWeight").val(), 2);
        var tareWeight = verasurMain.redondeo($("#mainbundle_ingress_tareWeight").val(), 2);
        var clean = verasurMain.redondeo(grossWeight - tareWeight, 2);
        return clean;
    };

    var calculateRealLiters = function () {
        var clean = verasurMain.redondeo($("#mainbundle_ingress_clean").val(), 2);
        var density = verasurMain.redondeo($("#mainbundle_ingress_density").val(), 3);
        if (density == 0) {
            realLiters = "";
        } else {
            var realLiters = verasurMain.redondeo(clean / density, 2);
        }
        return realLiters;
    };

    return {
        init: Initialize
    };

}();