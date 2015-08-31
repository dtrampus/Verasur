var verasurNewEditMovement = function() {

    var Initialize = function() {
        //$(".datepicker").datepicker('setDate', new Date());
        $("select").removeClass("form-control");
        
        $("#mainbundle_ingress_grossWeight,#mainbundle_ingress_tareWeight,#mainbundle_egress_grossWeight,#mainbundle_egress_tareWeight").keyup(function(){
            $("#mainbundle_ingress_clean,#mainbundle_egress_clean").val(calculateClean());
            $("#mainbundle_ingress_realLiter,#mainbundle_egress_realLiter").val(calculateRealLiters());
        });
        
        $("#mainbundle_ingress_density,#mainbundle_egress_density").keyup(function(){
            $("#mainbundle_ingress_realLiter,#mainbundle_egress_realLiter").val(calculateRealLiters());
        });
        
        
        //formateo de campos de texto
        $("#mainbundle_ingress_grossWeight,#mainbundle_ingress_tareWeight,#mainbundle_egress_grossWeight,#mainbundle_egress_tareWeight").focusout(function(){
            $(this).val(redondeo($(this).val(),2));
        });  
        $("#mainbundle_ingress_density,#mainbundle_egress_density").focusout(function(){
            $(this).val(redondeo($(this).val(),3));
        }); 
    };
    
    var calculateClean = function (){
        var grossWeight = redondeo($("#mainbundle_ingress_grossWeight,#mainbundle_egress_grossWeight").val(),2);
        var tareWeight = redondeo($("#mainbundle_ingress_tareWeight,#mainbundle_egress_tareWeight").val(),2);
        var clean = redondeo(grossWeight-tareWeight,2);
        return clean;
    };
    
    var calculateRealLiters = function(){
        var clean = redondeo($("#mainbundle_ingress_clean,#mainbundle_egress_clean").val(), 2);
        var density = redondeo($("#mainbundle_ingress_density,#mainbundle_egress_density").val(), 3);
        if (density == 0) {
            realLiters = "Infinito";
        } else {
            var realLiters = redondeo(clean / density, 2);
        }
        return realLiters;
    }
    
    var redondeo = function(value, precision){
        if (value == "") {
            return 0;
        } else {
            return parseFloat(parseFloat(value).toFixed(precision));
        }    
    }
    
    return {
        init: Initialize
//        init: function(translations) {
//            Initialize(translations);
//        }
        //calculateClean: calculateClean
    };

}();