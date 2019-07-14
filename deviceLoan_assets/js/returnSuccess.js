const $loadActivityICO = $(".loadActivityICO");

$("#renewLoan").on('submit', function (e) {
    $loadActivityICO.removeClass('hidden');
    
    var barcode = $("input[name='barcode']").val();
    
    e.preventDefault();
    
   isScanned(barcode);
});

function isScanned(barcode) {   
    $.post('../DeviceBorrow/isScanned', {'barcode': barcode})
            .done(function (isScannedResponse) {              
                if (isScannedResponse === '0') { 
                    alert("You must scan this device to continue with the renewal");                  
                } else {
                    $('#renewLoan')[0].submit();
                }
            })
            .fail(function (response) {
                alert("Failed to receive data  for this device. Please try again. If the error continues, please submit a new loan and contact your supervisor about this error");
                console.log(response);
            })
            .always(function () {
                $loadActivityICO.addClass('hidden');
                console.log("Ajax device scan completed");
            });
            
}