$(document).ready(function () {
    
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


    /*** Add form confirm: Applied to all type="submit" elements ***/

    $(document).on('submit', 'form[data-confirm]', function (e) {
        if (!confirm($(this).data('confirm'))) {
            e.stopImmediatePropagation(); //stops bubbling up the document; no other event handelrs will be triggered
            e.preventDefault(); //prevents event handlers of the button to trigger
        }
    });

    /*
     $("#submit").on('click', function(e) {
     $fileInput = $(".photoInput");
     $fileInput.each(function(index) {
     if($(this).val() == "") {
     e.preventDefault();
     alert("You must take pictures.");
     return(false); //quit loop early
     }
     });
     });
     */
}) //end of file
