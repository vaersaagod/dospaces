$(document).ready(function() {
    var spacesChangeExpiryValue = function () {
        var parent = $(this).parents('.field'),
            amount = parent.find('.do-expires-amount').val(),
            period = parent.find('.do-expires-period select').val();

        var combinedValue = (parseInt(amount, 10) === 0 || period.length === 0) ? '' : amount + ' ' + period;

        parent.find('[type=hidden]').val(combinedValue);
    };

    $('.do-expires-amount').keyup(spacesChangeExpiryValue).change(spacesChangeExpiryValue);
    $('.do-expires-period select').change(spacesChangeExpiryValue);
});
