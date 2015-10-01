var verasurNewEditTank = function () {

    var Initialize = function () {
        //$(".datepicker").datepicker('setDate', new Date());
        $("select").removeClass("form-control");

        $("#textarea").hide();
        $("#mainbundle_tank_status").change(function () {
            var status = $(this).select2('val');
            
            if (status == "Rechazado") {
                $("#textarea").show();
            } else {
                $("#mainbundle_tank_reason").val("");
                $("#textarea").hide();
            }
        });
        $("#mainbundle_tank_status").trigger("change");

        $("select").select2({
            maximumSelectionSize: 1,
            formatSelectionTooBig: function (limit) {
                return 'Los tanques solo pueden contener 1 producto!';
            }
        });

        if ($(".plant").is(':checked')) {
            $("select").select2({
                maximumSelectionSize: 100
            });
        }

        $(".plant").change(function () {
            $('.totalCapacity').val(null);
            if ($(".plant").is(':checked')) {
                $(".vertical").prop('disabled', true);
                $(".circumference").prop('disabled', true);
                $(".reference").prop('disabled', true);
                $(".coordinates").prop('disabled', true);
                $(".vertical").prop('checked', false);
                $('.circumference').val(null);
                $('.reference').val(null);
                $('.coordinates').val(null);
                $('.diameter').val(null);
                $('.liter').val(null);
                $('.select2').val(null);
                $("select").select2({
                    maximumSelectionSize: 100
                });
            } else {
                $(".vertical").prop('disabled', false);
                $(".circumference").prop('disabled', false);
                $(".reference").prop('disabled', false);
                $(".coordinates").prop('disabled', false);
                $('.select2').val(null);
                $("select").select2({
                    maximumSelectionSize: 1,
                    formatSelectionTooBig: function (limit) {
                        return 'Los tanques solo pueden contener 1 producto!';
                    }
                });
            }
        });

        if ($(".plant").is(':checked')) {
            $(".vertical").prop('disabled', true);
            $(".circumference").prop('disabled', true);
            $(".reference").prop('disabled', true);
            $(".coordinates").prop('disabled', true);
            $(".vertical").prop('checked', false);
        } else {
            $(".vertical").prop('disabled', false);
            $(".circumference").prop('disabled', false);
            $(".reference").prop('disabled', false);
            $(".coordinates").prop('disabled', false);
        }

        $(".circumference").focusout(function () {
            var val = $('.circumference').val();
            if (val != 0) {
                if (!isNaN(val)) {
                    var newVal = parseFloat($('.circumference').val(), 10).toFixed(2);
                    var pi = "3.1416";
                    var result_d = val / pi;
                    var result_diameter = result_d.toFixed(2);
                    var divicion = result_diameter / 1000;
                    var result_l = (divicion * divicion) / 4 * pi * 10;
                    var result_liter = result_l.toFixed(2);
                    $('.circumference').val(newVal);
                    $('.diameter').val(result_diameter);
                    $('.liter').val(result_liter);
                } else {
                    $('.diameter').val(null);
                    $('.liter').val(null);
                }
            } else {
                $('.circumference').val(null);
                $('.diameter').val(null);
                $('.liter').val(null);
            }
        });

        $(".reference").focusout(function () {
            var result_liter = $('.liter').val();
            var val_2 = $('.reference').val();
            if (val_2 != 0) {
                if (!isNaN(val_2)) {
                    var newVal_2 = parseFloat($('.reference').val(), 10).toFixed(2);
                    var result_Capacity = result_liter * (val_2 - 80);
                    var result_totalCapacity = result_Capacity.toFixed(1);
                    $('.reference').val(newVal_2);
                    $('.totalCapacity').val(result_totalCapacity);
                } else {
                    $('.totalCapacity').val(null);
                }
            } else {
                $('.reference').val(null);
                $('.totalCapacity').val(null);
            }
        });

        $(".coordinates").focusout(function () {
            var val_3 = $('.coordinates').val();
            if (val_3 != 0) {
                if (!isNaN(val_3)) {
                    var newVal_3 = parseFloat($('.coordinates').val(), 10).toFixed(2);
                    $('.coordinates').val(newVal_3);
                }
            } else {
                $('.coordinates').val("0");
            }
        });

        $(".totalCapacity").focusout(function () {
            var val_4 = $('.totalCapacity').val();
            if (val_4 != 0) {
                if (!isNaN(val_4)) {
                    var newVal_4 = parseFloat($('.totalCapacity').val(), 10).toFixed(2);
                    $('.totalCapacity').val(newVal_4);
                }
            } else {
                $('.totalCapacity').val(null);
            }
        });

        $('#tank-form').validate({
            submitHandler: function (form) {
                form.submit();
                $("#mainbundle_tank_submit").prop('disabled', true);
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
                "mainbundle_tank[code]": {
                    required: true
                },
                "mainbundle_tank[description]": {
                    required: true
                },
                "mainbundle_tank[circumference]": {
                    required: true,
                    number: true
                },
                "mainbundle_tank[reference]": {
                    required: true,
                    number: true
                },
                "mainbundle_tank[coordinates]": {
                    required: false,
                    number: true
                },
                "mainbundle_tank[diameter]": {
                    required: false
                },
                "mainbundle_tank[liter]": {
                    required: false
                },
                "mainbundle_tank[totalCapacity]": {
                    required: true,
                    number: true
                },
                "mainbundle_tank[products][]": {
                    required: true
                },
                reason: {
                    required: true
                }
            },
            messages: {
                "mainbundle_tank[code]": {
                    required: "Este campo es requerido"
                },
                "mainbundle_tank[description]": {
                    required: "Este campo es requerido"
                },
                "mainbundle_tank[circumference]": {
                    required: "Este campo es requerido",
                    number: "El campo solo admite números"
                },
                "mainbundle_tank[reference]": {
                    required: "Este campo es requerido",
                    number: "El campo solo admite números"
                },
                "mainbundle_tank[coordinates]": {
                    number: "El campo solo admite números"
                },
                "mainbundle_tank[totalCapacity]": {
                    required: "Este campo es requerido",
                    number: "El campo solo admite números"
                },
                "mainbundle_tank[products][]": {
                    required: "Este campo es requerido"
                },
                reason: {
                    required: "Este campo es requerido"
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
