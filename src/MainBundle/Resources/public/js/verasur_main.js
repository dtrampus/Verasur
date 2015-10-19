var verasurMain = function () {
    
    var Initialize = function(){
        jQuery.validator.setDefaults({
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
            }
        });
    };
    
    var redondeo = function (value, precision) {
        if (value == "" || isNaN(value)) {
            return 0;
        } else {
            return parseFloat(parseFloat(value).toFixed(precision));
        }
    };

    return {
        redondeo: redondeo,
        init: Initialize
    };

}();

