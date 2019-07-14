$(document).ready(function () {

    const $loadActivityICO = $(".loadActivityICO");

    /* Functions */

    function insertDatePicker(className) {
        $(className).datepicker({
            dateFormat: 'yy-mm-dd'
        });
    }

    /*Floatthead*/
    function addFloatThead(tableLocation, tableWrapperClass) {
        let $table = $(tableLocation);
        $table.floatThead({
            scrollContainer: function scrollContainer($table) {
                return $table.closest(tableWrapperClass);
            }
        });
    }

    function iniFloathead() {
        if ($(".table-wrapper").height() > 250) {
            $(".table-wrapper").css({"height": "350px"});
            $(".table-wrapper").css({"overflow-y": "scroll"});
            addFloatThead("table.stickyHeader", ".table-wrapper");  //Initialize inner floatThead on existing tables
        }
    }

    /*Device history*/
    function setHistoryCount() {
        //get hidden loans count and set it to the visible loans count
        let loanCount = $('span#count').text();
        $('span#loansCount').html("Loans: " + loanCount);
    }

    function getDeviceHistory(id) {
        $loadActivityICO.removeClass('hidden');

        $.post('../DeviceManage/deviceHistory', {"Id": id}, function (historyResponse) {
            $("#historyContent").html(historyResponse);

        })
                .done(function () {
                    iniFloathead();
                    //set hidden input device id for range search
                    $('input#deviceId').val(id);

                    setHistoryCount();
                    //enable search history
                    $("#searchRange").prop('disabled', false);
                })
                .fail(function (response) {
                    console.error(response);
                })
                .always(function () {
                    $loadActivityICO.addClass('hidden');
                    console.log("History ajax completed");
                });
    }

    /*Insert datepicker*/
    insertDatePicker(".rangeInput");

    /*Edit Barcode*/

    $("form#updateBarcode").on('submit', function (e) {
        e.preventDefault();
        let deviceId = $(this).children('input').val();
        let formString =
                '<form action="../DeviceManage/updateBarcode" method="POST">' +
                '<fieldset class="flexContainerCol">' +
                '<div id="addDevicebutton" class="textCenter">' +
                '<div class="subHeadings subHeading">Update barcode</div>' +
                '<div class="flexContainerCol">' +
                '<p>Enter the new barcode for this device below</p>' +
                '<input type="hidden" name="Id" value="' + deviceId + '" required/>' +
                '<input style="width:20%;" type="number" name="Barcode" required/>' +
                '</div>' +
                '<button class="button submit" name="submit" value="updateBarcode" type="submit">Submit</button>' +
                '</div>' +
                '</div>' +
                '</fieldset>' +
                '</form>';
        $("#editDevicebarcode").html(formString);
    });

    $("form#editNotes").on('submit', function (e) {
        e.preventDefault();

        $loadActivityICO.removeClass('hidden');

        let deviceId = $(this).children('input').val(); //get device id from current device input

        $("#deviceId_Note").val(deviceId); //set hidden input value

        $.post('../DeviceManage/getNotes', {'deviceId': deviceId})
                .done(function (textResponse) {
                    if( textResponse == 0 ) {
                       alert ( 'The device data was not received. Please try again.' );
               console.log(textResponse);
                       location.reload();
                    } else if( textResponse === "error" ) {
                        alert("Sever error getting data for this device. Please try again.");
                        location.reload();
                    } else {
                        $("#notesText").html(textResponse);
                    }
                })
                .fail(function (response) {
                    alert("Failed to get the notes. Please try again.");
                    console.error(response);
                })
                .always(function () {
                    $loadActivityICO.addClass('hidden');
                    console.log("Notes ajax completed");
                });

    });

    /*History*/

    $("#rangeHistory").on('submit', function (e) {
        $loadActivityICO.removeClass('hidden');
        e.preventDefault();

        let data = $(this).serialize();
        $.post('../DeviceManage/deviceHistoryRange', {"data": data}, function (historyResponse) {
            $("#historyContent").html(historyResponse);
        })
                .done(function () {
                    iniFloathead();
                    //set hidden input device id for range search
                    setHistoryCount();
                    //enable search history
                    $("#searchRange").prop('disabled', false);
                })
                .fail(function (response) {
                    console.error(response);
                })
                .always(function () {
                    $loadActivityICO.addClass('hidden');
                    console.log("History ajax completed");
                });
    });

    $(".deviceTab").on('click', function () {
        let deviceId = $(this).prop('id');
        getDeviceHistory(deviceId);
    });

    /*Tabs remembered*/

    if (sessionStorage.getItem('focusedTab')) {
        let selectTab = sessionStorage.getItem('focusedTab');
        $("#" + selectTab).click();
    }

    $(".tabs-title").on('click', function () {
        let tabId = $(this).find('a').prop('id');
        sessionStorage.setItem('focusedTab', tabId); //this will save the currently clicked tab
    });

}) //end of file
