const withConfirm = (cb, prompt) => {
    const p = prompt || 'Are you sure?';
    const gotConfirmed = confirm(p);
    if (gotConfirmed) {
        cb();
    } else {
        return false;
    }
};

const withSelect2 = function (cb) {

    $(this).parents('tr').find('select').each(function (index) {
        if ($(this).data('select2')) {
            $(this).select2('destroy');
        }
    });

    cb.call(this);

    $('select').select2();
};

const parentEl = (ctx, selector) => {
    return $(ctx).parents(selector);
};

const validationErrorsHandler = (errorIndex, errorValue) => {
    let errorDomElement, error_index, errorMessage;
    errorDomElement = '' + errorIndex;
    let errorDomIndexArray = errorDomElement.split(".");
    errorDomElement = '.' + errorDomIndexArray[0];
    error_index = errorDomIndexArray[1];
    errorMessage = '<i class="fa fa-exclamation-circle fa-fw"></i>' + errorValue[0];
    if (errorDomIndexArray.length === 1) {
        $(errorDomElement).html(errorMessage);
    } else {
        $("tbody tr:eq(" + error_index + ")").find(errorDomElement).html(errorMessage);
    }
};

const UNPROCESSABLE_ENTITY = 422;
