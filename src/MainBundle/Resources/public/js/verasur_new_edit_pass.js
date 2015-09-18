var verasurNewEditPass = function () {

    var Initialize = function () {
        //$(".datepicker").datepicker('setDate', new Date());

        var pass_date = $("#pass_date").val();
        var pass_time = $("#pass_time").val();

        if (!pass_date) {
            var now = new Date();
            var day = ("0" + now.getDate()).slice(-2);
            var month = ("0" + (now.getMonth() + 1)).slice(-2);
            var today = now.getFullYear() + "-" + (month) + "-" + (day);
            var time = ("0" + (now.getHours())).slice(-2) + ":" + ("0" + (now.getMinutes())).slice(-2);
            $('#mainbundle_pass_date_date').val(today);
            $('#mainbundle_pass_date_time').val(time);
        }else{
            $('#mainbundle_pass_date_date').val(pass_date);
            $('#mainbundle_pass_date_time').val(pass_time);
        }

        $("#mainbundle_pass_product").html("");
        $("#mainbundle_pass_tank_origin").change(function () {
            var value = $("#mainbundle_pass_tank_origin").select2('val');
            if (value != 0) {
                $.ajax({
                    type: 'GET',
                    url: Routing.generate('getProductAjax', {id: value}),
                    dataType: "json",
                    success: function (jsonTanks) {
                        $('#mainbundle_pass_product').html("");
                        for (var tank in jsonTanks) {
                            var arrayTank = jsonTanks[tank];
                            $('#mainbundle_pass_product').append("<option value=" + arrayTank[0] + ">" + arrayTank[1] + " - " + arrayTank[2] + "</option>");
                        }
                    }
                });
            } else {
                $('#mainbundle_pass_product').html("");
            }
        });

        $("#mainbundle_pass_product").html("");
        $("#mainbundle_pass_tank_origin").change(function () {
            var value = $("#mainbundle_pass_tank_origin").select2('val');
            if (value != 0) {
                $.ajax({
                    type: 'GET',
                    url: Routing.generate('calculateCapacity', {id: value}),
                    dataType: "json",
                    success: function (jsonTank) {
                        if (jsonTank['tankOcupedCapacity'] != null) {
                            $('#cantidad1').html("<span class='help-block'>Ocupado en el tanque: " + jsonTank['tankOcupedCapacity'] + "</span>");
                        } else {
                            $('#cantidad1').html("<span class='help-block'>Ocupado en el tanque: 0 </span>");
                        }
                    }
                });
            }
        });

        $("#mainbundle_pass_product").html("");
        $("#mainbundle_pass_tank_destination").change(function () {
            var value = $("#mainbundle_pass_tank_destination").select2('val');
            if (value != 0) {
                $.ajax({
                    type: 'GET',
                    url: Routing.generate('calculateCapacity', {id: value}),
                    dataType: "json",
                    success: function (jsonTank) {
                        $('#cantidad2').html("<span class='help-block'>Capacidad disponible en el tanque: " + jsonTank['tankFreeCapacity'] + "</span>");
                    }
                });
            }
        });
        $("#mainbundle_pass_tank_origin,#mainbundle_pass_tank_destination").trigger("change");

        jQuery.validator.addMethod("notEqual", function (value, element, param) {
            return $('#mainbundle_pass_tank_origin').select2('val') != $('#mainbundle_pass_tank_destination').select2('val');
        }, "Los tanques son iguales");

        jQuery.validator.addMethod("maxOriginQuantity", function (value, element, param) {
            var valueT = parseInt($("#mainbundle_pass_tank_origin").select2('val'));
            var valueQ = parseFloat($("#mainbundle_pass_quantity").val());
            var response;
            if (valueT != 0) {
                $.ajax({
                    type: 'GET',
                    url: Routing.generate('calculateCapacity', {id: valueT}),
                    dataType: "json",
                    async: false,
                    success: function (jsonTank) {
                        response = valueQ <= jsonTank['tankOcupedCapacity'];
                    }
                });
            }
            return response;
        }, "La cantidad no puede superar a la del tanque de origen.");

        jQuery.validator.addMethod("maxDestinyQuantity", function (value, element, param) {
            var valueT2 = parseInt($("#mainbundle_pass_tank_destination").select2('val'));
            var valueQ2 = parseFloat($("#mainbundle_pass_quantity").val());
            var response;
            if (valueT2 != 0) {
                $.ajax({
                    type: 'GET',
                    url: Routing.generate('calculateCapacity', {id: valueT2}),
                    dataType: "json",
                    async: false,
                    success: function (jsonTank) {
                        response = valueQ2 <= jsonTank['tankFreeCapacity'];
                    }
                });
            }
            return response;
        }, "La cantidad no puede superar a la del tanque de destino.");

        jQuery.validator.addMethod("heigherThan0", function (value, element, param) {
            var quant = parseFloat($("#mainbundle_pass_quantity").val());
            var response = quant >= 0.01;
            return response;
        }, "La cantidad tiene que ser mayor a 0");

        $('#pass-form').validate({
            submitHandler: function(form) {
                    form.submit();
                    $("#mainbundle_pass_submit").prop('disabled', true);
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
                tank_1: {
                    required: true,
                    notEqual: true
                },
                quantity: {
                    required: true,
                    number: true,
                    maxOriginQuantity: true,
                    maxDestinyQuantity: true,
                    heigherThan0: true
                },
                tank_2: {
                    required: true,
                    notEqual: true
                },
                product: {
                    required: true
                },
                date: {
                    required: true
                },
                time: {
                    required: true
                },
                observation: {
                    required: false
                }
            },
            messages: {
                tank_1: {
                    required: "Este campo es requerido",
                    notEqual: "Los tanques no deben ser iguales"
                },
                quantity: {
                    required: "Este campo es requerido",
                    number: "El valor ingresado debe ser un número",
                    maxOriginQuantity: "La cantidad no puede superar a la del tanque de origen",
                    maxDestinyQuantity: "La cantidad no puede superar a la del tanque de destino",
                    heigherThan0: "La cantidad tiene que ser mayor que 0"
                },
                tank_2: {
                    required: "Este campo es requerido",
                    notEqual: "Los tanques no deben ser iguales"
                },
                product: {
                    required: "Este campo es requerido"
                },
                date: {
                    required: "Este campo es requerido"
                },
                time: {
                    required: "Este campo es requerido"
                }
            }
        });
        $("select").removeClass("form-control").on("change", function (e) {
            $(this).valid();
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