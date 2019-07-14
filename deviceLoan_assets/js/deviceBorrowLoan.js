$(document).ready(function () {

    const $loadActivityICO = $(".loadActivityICO");

    /*Signature handling*/
    $("#signAgree").on('click', function () {
        //if no signature
        if (signaturePad.isEmpty()) {
            alert("You must read and sign the agreement before continuing.");
        } else {
            const data = signaturePad.toDataURL(); // save image as PNG base64
            //window.open(data); //test data
            $("#signatureInput").val(data);
            //if the agreement is signed, enable the accordions and select the contact accordion
            var accordion = $("#borrowerAccordion");
            accordion.removeAttr('disabled');
            accordion.foundation('down', $('#contactAccordion'));
            $(this).prop('disabled', true);
            //$("#testImg").attr('src', $("#signatureInput").val()); use image
        }
    });

//when the contact accordtion continue button is clicked, move to the office use accordion and keep it disabled
    $("#contactContinueBtn").on('click', function () {
        var accordion = $("#officeUseAccordion");
        accordion.removeAttr('disabled');
        //$("#borrowerAccordion").foundation('toggle', $("#contactAccordion"));
        accordion.foundation('down', $("#conditionAccordion"));
        accordion.attr('disabled', true);
        $(this).prop('disabled', true);
        alert("Thank you. Please return this device to the employee assisting you.");
    });

    /*File upload style*/
    function readURL(inputSelected, imgId) {
        //console.log(inputSelected.files);
        var reader = new FileReader();

        var file = inputSelected.files[0];

        reader.onload = function (e) {
            //console.log(imgId);
            var fileContent = e.target.result; //e.target holds the instance of FileReader, result holds the file contents base64 or error on failure
            document.getElementById(imgId).src = fileContent;
        }

        if (file) {
            reader.readAsDataURL(file); //reads Blob and returns a data: URL representing the data. ie. show the image uploaded
        }
        //console.log('LOADING', reader.readyState); // readyState will be 1

        reader.onerror = function () {
            alert("Something went wrong, the selected file could not be uploaded. Please try again.");
            console.error("Error reading the file. Code: " + event.target.error.code);
        }

    }

    $(".photoInput").change(function () {
        //var photoId = $(this).attr('id'); //Id for the changed input
        var imgId = $(this).next('label').children().attr('id'); //Id of the image inside the label tag
        readURL(this, imgId);
    });

    /*handle selected device and photos*/
    $("#deviceScan").on('click', function (e) {
        var id = $("#deviceSelect").val();

        $loadActivityICO.removeClass('hidden');

        $.post('../index.php/deviceBorrow/DeviceBorrow_DeviceView', {deviceId: id})
                .done(function (device_accessories_response) {

                    //Check photos have been taken and selected
                    var $fileInput = $(".photoInput");
                    var flag = true;
                    $fileInput.each(function (index) {
                        if ($(this).val() == "") {
                            e.preventDefault();
                            alert("You must take pictures of the front, back and home screen of the device to show its condition before submitting this loan.");
                            flag = false;
                            return(false); //quit loop early
                        }
                    });
                    if (flag) {
                        if (device_accessories_response === "NotLoaned") {
                            alert("You must scan this device before continuing.");
                        } else if (device_accessories_response === "NotValid") {
                            alert("This barcode does not exist in ---. Please select another device and report this problem to your supervisor.");
                        } else {
                            $("#device_accessories").html(device_accessories_response);

                            //Open the device and accessories accordion.
                            var accordion = $("#officeUseAccordion");
                            accordion.removeAttr('disabled');
                            accordion.foundation('down', $("#device_accessories"));

                            //attach event handler for new html. Open employee accordion
                            $("#deviceAccessoriesBtn").on('click', function () {
                                var accordion = $("#officeUseAccordion");
                                accordion.foundation('down', $("#employeeAccordion"));
                                $("#borrowFormSubmit").attr('disabled', false);
                            });
                        }

                    }
                })
                .fail(function (response) {
                    alert("An error occurred retrieving the device. Please try again. If the problem persists, please use a paper form for this loan, then contact your supervisor immediately.");
                    console.error(response);
                })
                .always(function () {
                    $loadActivityICO.addClass('hidden');
                    console.log("Ajax device scan completed");
                });
    });

    /*borrow form submit*/
    $("#loanForm").on('submit', function (e) {
        var $submitBtn = $("#borrowFormSubmit");
        $submitBtn.attr('disabled', true);
        $loadActivityICO.removeClass('hidden');

        if (validate() == false) {
            e.preventDefault();
            $submitBtn.attr('disabled', false);
            $loadActivityICO.addClass('hidden');
        }
    });

    /*borrow form submit for validation form */
    $("#loanForm").on('submit', function (e) {
      
      $loadActivityICO.removeClass('hidden');

      var $submitBtn = $("#borrowFormSubmit_val");
      $submitBtn.attr('disabled', true);

        if (validate() == false) {
            e.preventDefault();
            $submitBtn.attr('disabled', false);
            $loadActivityICO.addClass('hidden');
        } else {
            var $fileInput = $(".photoInput");
            $fileInput.each(function (index) {
                if ($(this).val() === "") {
                    e.preventDefault();
                    $submitBtn.attr('disabled', false);
                    $loadActivityICO.addClass('hidden');
                    alert("You must take pictures of the front, back and home screen of the device to show its condition before submitting this loan.");
                    return(false); //quit loop early
                }
            });
        }
    });

    function validate() {
        var phoneVal = $("#phone").val();
        var emailVal = $("#email").val();

        if (phoneVal === "" || phoneVal === " " || $.isNumeric(phoneVal) == false) {
            alert("You must enter a valid phone number");
            return false;
        } else if (emailVal === "" || emailVal === " " || (emailVal.indexOf("@") == -1)) {
            alert("You must enter a valid email address");
            return false;
        }
    }
}); //end of file
