var verasurNewEditPass = function () {

    var Initialize = function () {
        //$(".datepicker").datepicker('setDate', new Date());

        var tank_id_for_change2 = $("#tank_id_for_change2").val();
        var product_id_for_change = $("#product_id_for_change").val();

//        $("#mainbundle_pass_product").html("");
        var variable = "";
        $("#mainbundle_new_pass_tank_origin,#mainbundle_edit_pass_tank_origin").change(function () {
            var value = $(this).select2('val');
            if (variable == "1") {
                $('#mainbundle_pass_product').select2("val", "");
                $('#mainbundle_new_pass_tank_destination,#mainbundle_edit_pass_tank_destination').select2("val", "");
            }
            $('#cantidad2').html("");
            if (value != "" && typeof value != "undefined") {
                $.ajax({
                    type: 'GET',
                    url: Routing.generate('getProductAjax', {id: value}),
                    dataType: "json",
                    success: function (jsonProducts) {
                        $('#mainbundle_pass_product').html("<option value=''>Elige una opción</option>");
                        for (var product in jsonProducts) {
                            var arrayProduct = jsonProducts[product];
                            var id_jsonProduct = jsonProducts[product][0];
                            if (id_jsonProduct == product_id_for_change) {
                                $('#mainbundle_pass_product').append("<option selected='selected' value=" + arrayProduct[0] + ">" + arrayProduct[1] + " - " + arrayProduct[2] + "</option>");
                            } else {
                                $('#mainbundle_pass_product').append("<option value=" + arrayProduct[0] + ">" + arrayProduct[1] + " - " + arrayProduct[2] + "</option>");
                            }
                        }
                    }
                });
            } else {
                $('#cantidad1').html("");
                $('#cantidad2').html("");
                $('#mainbundle_pass_product').html("<option value=''>Elige una opción</option>");
                $('#mainbundle_pass_product').select2("val", "");
                $('#mainbundle_new_pass_tank_destination,#mainbundle_edit_pass_tank_destination').html("<option value=''>Elige una opción</option>");
                $('#mainbundle_new_pass_tank_destination,#mainbundle_edit_pass_tank_destination').select2("val", "");
            }
            return variable = "1";
        });

        var variable2 = "";
        var value_prod = $("#mainbundle_pass_product").select2('val');
        $("#mainbundle_pass_product").change(function () {
            var value2 = $(this).select2('val');
            $('#mainbundle_new_pass_tank_destination,#mainbundle_edit_pass_tank_destination').html("<option value=''>Elige una opción</option>");
            if (variable2 == "1") {
                $('#mainbundle_new_pass_tank_destination,#mainbundle_edit_pass_tank_destination').select2("val", "");
                $('#mainbundle_new_pass_tank_destination,#mainbundle_edit_pass_tank_destination').html("<option value=''>Elige una opción</option>");
                $('#mainbundle_new_pass_tank_destination,#mainbundle_edit_pass_tank_destination').trigger('change');
            }
            $('#cantidad2').html("");
            if (value2 != '' && typeof value2 != "undefined") {
                $.ajax({
                    type: 'GET',
                    url: Routing.generate('getTanksAjax', {id: value2}),
                    dataType: "json",
                    success: function (jsonTanks) {
                        $('#mainbundle_new_pass_tank_destination,#mainbundle_edit_pass_tank_destination').html("<option value=''>Elige una opción</option>");
                        for (var tank in jsonTanks) {
                            var arrayTank = jsonTanks[tank];
                            var id_jsonTank = jsonTanks[tank][0];
                            if (id_jsonTank == tank_id_for_change2) {
                                $('#mainbundle_edit_pass_tank_destination').append("<option selected='selected' value=" + arrayTank[0] + ">" + arrayTank[1] + " - " + arrayTank[2] + "</option>");
                                $('#mainbundle_edit_pass_tank_destination').trigger('change');
                            } else {
                                $('#mainbundle_new_pass_tank_destination,#mainbundle_edit_pass_tank_destination').append("<option value=" + arrayTank[0] + ">" + arrayTank[1] + " - " + arrayTank[2] + "</option>");
                            }
                        }
                    }
                });
            } else {
                $('#cantidad2').html("");
                $('#mainbundle_new_pass_tank_destination,#mainbundle_edit_pass_tank_destination').select2('val', "");
                $('#mainbundle_new_pass_tank_destination,#mainbundle_edit_pass_tank_destination').html("");
            }
            variable2 = "1";
        });

        //Codigo para nuevo pase:
        $("#mainbundle_new_pass_tank_origin").change(function () {
            var value = $("#mainbundle_new_pass_tank_origin").select2('val');
            if (value != 0) {
                $.ajax({
                    type: 'GET',
                    url: Routing.generate('calculateCapacity', {id: value}),
                    dataType: "json",
                    success: function (jsonTank) {
                        if (jsonTank['tankOcupedCapacity'] != null) {
                            $('#cantidad1').html("<span>Ocupado en el tanque: " + jsonTank['tankOcupedCapacity'] + "</span>");
                        } else {
                            $('#cantidad1').html("<span>Ocupado en el tanque: 0 </span>");
                        }
                    }
                });
            }
        });

        $("#mainbundle_new_pass_tank_destination").change(function () {
            var value = $("#mainbundle_new_pass_tank_destination,#mainbundle_edit_pass_tank_destination").select2('val');
            if (value != 0) {
                $.ajax({
                    type: 'GET',
                    url: Routing.generate('calculateCapacity', {id: value}),
                    dataType: "json",
                    success: function (jsonTank) {
                        $('#cantidad2').html("<span>Capacidad disponible en el tanque: " + jsonTank['tankFreeCapacity'] + "</span>");
                    }
                });
            }
        });


        var quantity = parseFloat($("#mainbundle_pass_quantity").val());
        if (isNaN(quantity)) {
            quantity = parseFloat("0");
        }
        //Codigo para editar pase ORIGEN:   
        var tank_id_for_change = $("#tank_id_for_change").val();
        $("#mainbundle_edit_pass_tank_origin").change(function () {
            var value = $("#mainbundle_edit_pass_tank_origin").select2('val');
            if (value != 0) {
                $.ajax({
                    type: 'GET',
                    url: Routing.generate('calculateCapacity', {id: value}),
                    dataType: "json",
                    success: function (jsonTank) {
                        if (jsonTank['tankOcupedCapacity'] != null) {
                            var ocupedInTank = jsonTank['tankOcupedCapacity'];
                            var quantity2 = quantity;
                            if (tank_id_for_change == value) {
                                var ocuped = parseFloat(ocupedInTank + quantity2).toFixed(2);
                            } else {
                                ocuped = ocupedInTank;
                            }
                            $('#cantidad1').html("<span>Ocupado en el tanque: " + ocuped + "</span>");
                        } else {
                            $('#cantidad1').html("<span>Ocupado en el tanque: 0 </span>");
                        }
                    }
                });
            }
        });

        //Codigo para editar pase DESTINO:
        $("#mainbundle_edit_pass_tank_destination").change(function () {
            var value = $("#mainbundle_edit_pass_tank_destination").select2('val');
            if (value != 0) {
                $.ajax({
                    type: 'GET',
                    url: Routing.generate('calculateCapacity', {id: value}),
                    dataType: "json",
                    success: function (jsonTank) {
                        var freeInTank = jsonTank['tankFreeCapacity'];
                        var quantity3 = quantity;
                        if (tank_id_for_change2 == value) {
                            var free = parseFloat(freeInTank - quantity3).toFixed(2);
                        } else {
                            free = freeInTank;
                        }
                        $('#cantidad2').html("<span>Capacidad disponible en el tanque: " + free + "</span>");
                    }

                });
            }
        });
        $("#mainbundle_edit_pass_tank_destination,#mainbundle_edit_pass_tank_origin,#mainbundle_pass_product").trigger("change");

        //JQuery Validate:
        jQuery.validator.addMethod("notEqual", function (value, element, param) {
            return $('#mainbundle_new_pass_tank_origin,#mainbundle_edit_pass_tank_origin').select2('val') != $('#mainbundle_new_pass_tank_destination,#mainbundle_edit_pass_tank_destination').select2('val');
        }, "Los tanques son iguales");

        jQuery.validator.addMethod("maxOriginQuantity", function (value, element, param) {
            var valueT = parseInt($("#mainbundle_new_pass_tank_origin,#mainbundle_edit_pass_tank_origin").select2('val'));
            var valueQ = parseFloat($("#mainbundle_pass_quantity").val());
            var response;
            if (valueT != 0) {
                $.ajax({
                    type: 'GET',
                    url: Routing.generate('calculateCapacity', {id: valueT}),
                    dataType: "json",
                    async: false,
                    success: function (jsonTank) {
                        var tankOcupedCapacity = parseFloat(jsonTank['tankOcupedCapacity'] + quantity).toFixed(2);
                        response = valueQ <= tankOcupedCapacity;
                    }
                });
            }
            return response;
        }, "La cantidad no puede superar a la del tanque de origen");

        jQuery.validator.addMethod("maxDestinyQuantity", function (value, element, param) {
            var valueT2 = parseInt($("#mainbundle_new_pass_tank_destination,#mainbundle_edit_pass_tank_destination").select2('val'));
            var valueQ2 = parseFloat($("#mainbundle_pass_quantity").val());
            var response;
            if (valueT2 != 0) {
                $.ajax({
                    type: 'GET',
                    url: Routing.generate('calculateCapacity', {id: valueT2}),
                    dataType: "json",
                    async: false,
                    success: function (jsonTank) {
                        var tankFreeCapacity = parseFloat(jsonTank['tankFreeCapacity'] + quantity).toFixed(2);
                        response = valueQ2 <= tankFreeCapacity;
                    }
                });
            }
            return response;
        }, "La cantidad no puede superar a la del tanque de destino");

        //JQuery validate
        jQuery.validator.addMethod("heigherThan0", function (value, element, param) {
            var quant = parseFloat($("#mainbundle_pass_quantity").val());
            var response = quant >= 0.01;
            return response;
        }, "La cantidad tiene que ser mayor a 0");

        $('#pass-form').validate({
            submitHandler: function (form) {
                var date = $("#mainbundle_pass_date_date").val();
                var time = $("#mainbundle_pass_date_time").val();
                var datetime = date.concat(" ").concat(time);
                var pass_datetime = $("#pass_datetime").val();
                if (typeof pass_datetime == 'undefined' || pass_datetime != datetime) {
                    $.ajax({
                        type: 'GET',
                        url: Routing.generate('checkDateTime', {date: datetime}),
                        dataType: "json",
                        success: function (jsonDate) {
                            if (jsonDate == false) {
                                $('#mainbundle_pass_date_time').closest('.form-group').append("<span for='mainbundle_pass_date_time' class='help-block'>Ya se ha cargado un pase con esta fecha, hora y minutos!</span>").addClass('has-error');
                            } else {
                                form.submit();
                                $("#mainbundle_pass_submit").prop('disabled', true);
                            }
                        }
                    });
                } else {
                    form.submit();
                    $("#mainbundle_pass_submit").prop('disabled', true);
                }
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
