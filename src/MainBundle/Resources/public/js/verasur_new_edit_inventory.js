var verasurNewEditInventory = function () {

    var Initialize = function () {
        //$(".datepicker").datepicker('setDate', new Date());
        $("select").removeClass("form-control");

        var tankId = $("#tankId").val();
        var coordinates = $("#tankCoordinates").val();
        var tankOrPlant = $("#tankOrPlant").val();
        var tankLiter = $("#tankLiter").val();

        //false = tanque
        if (tankOrPlant == false) {
            $("#mainbundle_inventory_liter").prop('readonly', true);
            $("#mainbundle_inventory_vacuum").focusout(function () {
                if ($("#mainbundle_inventory_vacuum").val() != "") {
                    var vacuum = parseFloat($('#mainbundle_inventory_vacuum').val(), 10).toFixed(2);
                    $('#mainbundle_inventory_vacuum').val(vacuum);
                    var diference = vacuum - coordinates;
                    var result = diference * tankLiter;
                    var result_fixed = result.toFixed(2);
                    if (!isNaN(result)) {
                        $("#mainbundle_inventory_liter").val(result_fixed);
                    } else {
                        $("#mainbundle_inventory_liter").val(null);
                    }
                } else {
                    $("#mainbundle_inventory_liter").val(null);
                }
            })
        } else {
            $("#mainbundle_inventory_vacuum").prop('disabled', true);
        }

        $.ajax({
            type: 'GET',
            url: Routing.generate('getProductAjax', {id: tankId}),
            dataType: "json",
            success: function (jsonTanks) {
                $('#mainbundle_inventory_products').html("<option value=''>Elige una opcion</option>");
                for (var tank in jsonTanks) {
                    var arrayTank = jsonTanks[tank];
                    $('#mainbundle_inventory_products').append("<option value=" + arrayTank[0] + ">" + arrayTank[1] + " - " + arrayTank[2] + "</option>");
                }
            }
        });
        
        $("#mainbundle_inventory_vacuum").focusout(function () {
        if(isNaN($("#mainbundle_inventory_vacuum").val())){
           $("#mainbundle_inventory_vacuum").val(null);
        }
    });

        $('#inventory-form').validate({
            submitHandler: function(form) {
                    form.submit();
                    $("#mainbundle_inventory_submit").prop('disabled', true);
                },
            highlight: function (element) {
                $(element).closest('.form-group').addClass('has-error');
            },
            unhighlight: function (element) {
                $(element).closest('.form-group').removeClass('has-error');
            },
            errorElement: 'span',
            errorClass: 'help-block',
            errorPlacement: function (error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else {
                    error.insertAfter(element);
                }
            },
            rules: {
                "mainbundle_inventory[date][date]": {
                    required: true
                },
                "mainbundle_inventory[date][time]": {
                    required: true
                },
                "mainbundle_inventory[product]": {
                    required: true
                },
                "mainbundle_inventory[vacuum]": {
                    required: true,
                    number: true
                },
                "mainbundle_inventory[liter]": {
                    required: true,
                    number: true
                }
            },
            messages: {
                "mainbundle_inventory[date][date]": {
                    required: "Este campo es requerido"
                },
                "mainbundle_inventory[date][time]": {
                    required: "Este campo es requerido"
                },
                "mainbundle_inventory[product]": {
                    required: "Este campo es requerido"
                },
                "mainbundle_inventory[vacuum]": {
                    required: "Este campo es requerido",
                    number: "El campo solo admite números"
                },
                "mainbundle_inventory[liter]": {
                    required: "Este campo es requerido",
                    number: "El campo solo admite números"
                }
            }
        });
        
    };

    return {
        init: Initialize
//        init: function(translations) {
//            Initialize(translations);
//        }
                //calculateClean: calculateClean
    };

}();