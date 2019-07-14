$(document).ready(function () {
    const $loadActivityICO = $(".loadActivityICO");

//When continue button is clicked in the device accordion, check the device is scanned. If so, enable the other accordions and the submit button
    $("#deviceScan").on('click', function () {
        var accordion = $("#officeUseAccordion");
        var deviceBarcode = $('input[name="DeviceBarcode"]').val();
        $loadActivityICO.removeClass('hidden');

        $.post('../deviceBorrow/isScanned', {barcode: deviceBarcode})
                .done(function (boolResponse) {

                    if (boolResponse == 1) {
                        accordion.removeAttr('disabled');
                        accordion.foundation('up', $('#device_accessories'));
                        accordion.foundation('down', $('#conditionAccordion'));
                        accordion.foundation('down', $('#employeeAccordion'));
                        $('button[type="submit"]').prop('disabled', false);
                    } else {
                        alert("Please, scan this device in before completing the return form.");
                    }
                })
                .fail(function (response) {
                    console.error(response);
                })
                .always(function (response) {
                    $loadActivityICO.addClass('hidden');
                });
    });

    /*borrow form submit handling*/
    $("#returnForm").on('submit', function (e) {
      var $submitBtn = $("#returnFormSubmit");

      $loadActivityICO.removeClass('hidden');
      $submitBtn.attr('disabled', true);

        var $fileInput = $(".photoInput");

        $fileInput.each(function (index) {
            if ($(this).val() == "") {
                e.preventDefault();
                $submitBtn.attr('disabled', false);
                $loadActivityICO.addClass('hidden');
                
                alert("You must take pictures of the front, back and home screen of the device to show its condition before returning this loan.");
                return(false); //quit loop early
            }
        });
    });

}); //end of file
